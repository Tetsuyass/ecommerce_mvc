<?php

namespace App\Controllers;

use App\Models\Produit;

class ProductController {

    /**
     * GET /produits
     * Liste des produits
     */
    public function index() {
        header("Content-type: application/json");

        $categorieId = $_GET['categorie'] ?? null;

        if ($categorieId) {
            $produits = Produit::getByIdCategorie($categorieId);
        } else {
            $produits = Produit::getAll();
        }

        foreach ($produits as &$produit) {
            if (!empty($produit['image_produit'])) {
                $produit['image_produit'] = "http://localhost:8080/images/" . basename($produit['image_produit']);
            }
        }

        echo json_encode([
            'success' => true,
            'data' => $produits
        ]);
    }

    /**
     * GET /products/{id}
     * Détail d'un produit
     */
    public function show($id) {
        header("Content-type: application/json");

        $produit = Produit::getById($id);

        if (!$produit) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Produit introuvable'
            ]);
            return;
        }

        if (!empty($produit['image_produit'])) {
            $produit['image_produit'] = "http://localhost:8080/images/" . basename($produit['image_produit']);
        }

        echo json_encode([
            'success' => true,
            'data' => $produit
        ]);
    }

    /**
     * DELETE /products/{id}
     * Suppression produit (ADMIN)
     */
    public function delete($id)
    {
        header('Content-Type: application/json');

        $deleted = Produit::deleteById($id);

        if (!$deleted) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Produit introuvable'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Produit supprimé'
        ]);
    }
}
