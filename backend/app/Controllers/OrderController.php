<?php

namespace App\Controllers;

use App\Models\Commandes;
use App\Models\Ligne_Commande;
use App\Models\Produit;

class OrderController
{
    /**
     * POST /orders
     * Validation du panier → création commande
     */
    public function store()
    {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['client_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ]);
            return;
        }

        if (empty($_SESSION['cart'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Panier vide'
            ]);
            return;
        }

        $cart = $_SESSION['cart'];
        $montantTotal = 0;

        foreach ($cart as $id_produit => $quantite) {
            $produit = Produit::getById($id_produit);
            if ($produit) {
                $montantTotal += $produit['prix'] * $quantite;
            }
        }

        $commande = new Commandes();
        $commande->setIdClient($_SESSION['client_id']);
        $commande->setStatut('en attente');
        $commande->setMontantTotal($montantTotal);
        $commande->setAdresseLivraison($_SESSION['adresse'] ?? '');
        $commande->setVilleLivraison($_SESSION['ville'] ?? '');
        $commande->setCodePostalLivraison($_SESSION['code_postal'] ?? '');

        $commande->save();

        $id_commande = $commande->getIdCommande();

        foreach ($cart as $id_produit => $quantite) {

            $produit = Produit::getById($id_produit);
            if (!$produit) {
                continue;
            }

            $ligne = new Ligne_Commande();
            $ligne->setIdCommande($id_commande);
            $ligne->setIdProduit($id_produit);
            $ligne->setQuantite($quantite);
            $ligne->setPrixUnitaire($produit['prix']);
            $ligne->setSousTotal($produit['prix'] * $quantite);
            $ligne->save();

        }

        unset($_SESSION['cart']);

        echo json_encode([
            'success' => true,
            'message' => 'Commande validée',
            'id_commande' => $id_commande
        ]);
    }

    /**
     * GET /orders
     * Historique commandes client
     */
    public function index()
    {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['client_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ]);
            return;
        }

        $commandes = Commandes::findByIdClient($_SESSION['client_id']);

        echo json_encode([
            'success' => true,
            'data' => $commandes
        ]);
    }

    /**
     * GET /orders/{id}
     * Détail d’une commande
     */
    public function show($id_commande)
    {
        header('Content-Type: application/json');
        session_start();

        if (!isset($_SESSION['client_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ]);
            return;
        }

        $commande = Commandes::findByIdCommande($id_commande);

        if (!$commande || $commande['id_client'] != $_SESSION['client_id']) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Commande introuvable'
            ]);
            return;
        }

        $lignes = Ligne_Commande::findByIdCommande($id_commande);

        echo json_encode([
            'success' => true,
            'data' => [
                'commande' => $commande,
                'lignes' => $lignes
            ]
        ]);
    }
}
