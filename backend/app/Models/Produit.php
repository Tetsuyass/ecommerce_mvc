<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Produit {
    private $id_produit;
    private $id_categorie;
    private $nom_produit;
    private $description_produit;
    private $prix;
    private $stock;
    private $image_produit;
    private $statut_produit;

    public function getIdProduit() {return $this->id_produit;}
    public function getIdCategorie() {return $this->id_categorie;}
    public function getNomProduit() {return $this->nom_produit;}
    public function getDescriptionProduit() {return $this->description_produit;}
    public function getPrix() {return $this->prix;}
    public function getStock() {return $this->stock;}
    public function getImageProduit() {return $this->image_produit;}
    public function getStatutProduit() {return $this->statut_produit;}
    public function setIdProduit($id_produit) {$this->id_produit = $id_produit;}
    public function setIdCategorie($id_categorie) {$this->id_categorie = $id_categorie;}
    public function setNomProduit($nom_produit) {
        $this->nom_produit = $nom_produit;
    }

    public function setDescriptionProduit($description_produit) {
        $this->description_produit = $description_produit;
    }
    public function setPrix($prix) {$this->prix = $prix;}
    public function setStock($stock) {$this->stock = $stock;}
    public function setImageProduit($image_produit) {
        $this->image_produit = $image_produit;
    }

    public function setStatutProduit($statut_produit) {
        $this->statut_produit = $statut_produit;
    }

    public static function getAll() {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("SELECT * FROM produit ORDER BY id_produit DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id_produit) {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
        $stmt->bindParam(":id_produit", $id_produit);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByIdCategorie($id_categorie) {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_categorie = :id_categorie");
        $stmt->bindParam(":id_categorie", $id_categorie);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save() {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("
            INSERT INTO produit (id_categorie, nom_produit, description_produit, prix, stock, image_produit, statut_produit)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $this->getIdCategorie(),
            $this->getNomProduit(),
            $this->getDescriptionProduit(),
            $this->getPrix(),
            $this->getStock(),
            $this->getImageProduit(),
            $this->getStatutProduit()
        ]);
    }

    public function update() {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("
            UPDATE produit 
            SET id_categorie = ?,
                nom_produit = ?,
                description_produit = ?,
                prix = ?,
                stock = ?,
                image_produit = ?,
                statut_produit = ?
            WHERE id_produit = ?
        ");

        return $stmt->execute([
            $this->getIdCategorie(),
            $this->getNomProduit(),
            $this->getDescriptionProduit(),
            $this->getPrix(),
            $this->getStock(),
            $this->getImageProduit(),
            $this->getStatutProduit(),
            $this->getIdProduit()
        ]);
    }

    public static function deleteById($id_produit) {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
        $stmt->bindParam(":id_produit", $id_produit);
        return $stmt->execute();
    }
}
