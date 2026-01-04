<?php

namespace App\Controllers;

use App\Models\Produit;

class CartController
{
    /**
     * GET /cart
     * Affichage du panier
     */
    public function index()
    {
        header('Content-Type: application/json');
        session_start();

        $cart = $_SESSION['cart'] ?? [];
        $items = [];
        $total = 0;

        foreach ($cart as $id_produit => $quantite) {
            $produit = Produit::getById($id_produit);

            if (!$produit) {
                continue;
            }

            $sousTotal = $produit['prix'] * $quantite;
            $total += $sousTotal;

            $items[] = [
                'produit' => $produit,
                'quantite' => $quantite,
                'sous_total' => $sousTotal
            ];
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'items' => $items,
                'total' => $total
            ]
        ]);
    }

    /**
     * POST /cart/add
     * Ajouter un produit au panier
     */
    public function add()
    {
        session_start();

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_produit']) || empty($data['quantite'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Données invalides'
            ]);
            return;
        }

        $id_produit = (int) $data['id_produit'];
        $quantite = (int) $data['quantite'];

        if ($quantite <= 0) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Quantité invalide'
            ]);
            return;
        }

        // Vérifier que le produit existe
        if (!Produit::getById($id_produit)) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Produit introuvable'
            ]);
            return;
        }

        // Ajouter ou incrémenter
        if (isset($_SESSION['cart'][$id_produit])) {
            $_SESSION['cart'][$id_produit] += $quantite;
        } else {
            $_SESSION['cart'][$id_produit] = $quantite;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Produit ajouté au panier'
        ]);
    }

    /**
     * POST /cart/update
     * Modifier la quantité d’un produit
     */
    public function update()
    {
        header('Content-Type: application/json');
        session_start();

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_produit']) || !isset($data['quantite'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Données invalides'
            ]);
            return;
        }

        $id_produit = (int) $data['id_produit'];
        $quantite = (int) $data['quantite'];

        if (!isset($_SESSION['cart'][$id_produit])) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Produit non présent dans le panier'
            ]);
            return;
        }

        if ($quantite <= 0) {
            unset($_SESSION['cart'][$id_produit]);
        } else {
            $_SESSION['cart'][$id_produit] = $quantite;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Panier mis à jour'
        ]);
    }

    /**
     * POST /cart/remove
     * Supprimer un produit du panier
     */
    public function remove()
    {
        header('Content-Type: application/json');
        session_start();

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_produit'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID produit manquant'
            ]);
            return;
        }

        $id_produit = (int) $data['id_produit'];

        unset($_SESSION['cart'][$id_produit]);

        echo json_encode([
            'success' => true,
            'message' => 'Produit supprimé du panier'
        ]);
    }

    /**
     * POST /cart/clear
     * Vider le panier
     */
    public function clear()
    {
        header('Content-Type: application/json');
        session_start();

        unset($_SESSION['cart']);

        echo json_encode([
            'success' => true,
            'message' => 'Panier vidé'
        ]);
    }
}
