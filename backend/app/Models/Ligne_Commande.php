<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Ligne_Commande {
    private $id_commande;
    private $id_produit;
    private $quantite;
    private $prix_unitaire;
    private $sous_total;

    public function getIdCommande() {return $this->id_commande;}
    public function getIdProduit() {return $this->id_produit;}
    public function getQuantite() {return $this->quantite;}
    public function getPrixUnitaire() {return $this->prix_unitaire;}
    public function getSousTotal() {return $this->sous_total;}
    public function setIdCommande($id_commande) {$this->id_commande = $id_commande;}
    public function setIdProduit($id_produit) {$this->id_produit = $id_produit;}
    public function setQuantite($quantite) {$this->quantite = $quantite;}
    public function setPrixUnitaire($prix_unitaire) {$this->prix_unitaire = $prix_unitaire;}
    public function setSousTotal($sous_total) {$this->sous_total = $sous_total;}

    public static function getAll() {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM ligne_commande ORDER BY id_commande DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByIdCommande($id_commande) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM ligne_commande WHERE id_commande = :id_commande");
        $stmt->bindParam(':id_commande', $id_commande);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save() {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO ligne_commande (id_commande, id_produit, quantite, prix_unitaire, sous_total)
            VALUES (:id_commande, :id_produit, :quantite, :prix_unitaire, :sous_total)
        ");
        $stmt->bindValue(":id_commande", $this->getIdCommande());
        $stmt->bindValue(":id_produit", $this->getIdProduit());
        $stmt->bindValue(":quantite", $this->getQuantite());
        $stmt->bindValue(":prix_unitaire", $this->getPrixUnitaire());
        $stmt->bindValue(":sous_total", $this->getSousTotal());
        return $stmt->execute();
    }

    public function update() {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            UPDATE ligne_commande
            SET id_produit = :id_produit,
                quantite = :quantite, 
                prix_unitaire = :prix_unitaire,
                sous_total = :sous_total
            WHERE id_commande = :id_commande
        ");
        $stmt->bindValue(":id_produit", $this->getIdProduit());
        $stmt->bindValue(":quantite", $this->getQuantite());
        $stmt->bindValue(":prix_unitaire", $this->getPrixUnitaire());
        $stmt->bindValue(":sous_total", $this->getSousTotal());
        $stmt->bindValue(":id_commande", $this->getIdCommande());
    }

    public static function delete($id_commande) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM ligne_commande WHERE id_commande = :id_commande");
        $stmt->bindValue(":id_commande", $id_commande);
        return $stmt->execute();
    }

}
