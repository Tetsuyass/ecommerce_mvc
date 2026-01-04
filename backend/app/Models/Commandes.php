<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Commandes {
    private $id_commande;
    private $id_client;
    private $statut;
    private $montant_total;
    private $adresse_livraison;
    private $ville_livraison;
    private $code_postal_livraison;

    public function getIdCommande() {return $this->id_commande;}
    public function getIdClient() {return $this->id_client;}
    public function getStatut() {return $this->statut;}
    public function getMontantTotal() {return $this->montant_total;}
    public function getAdresseLivraison() {return $this->adresse_livraison;}
    public function getVilleLivraison() {return $this->ville_livraison;}
    public function getCodePostalLivraison() {return $this->code_postal_livraison;}
    public function setIdCommande($id_commande) {$this->id_commande = $id_commande;}
    public function setIdClient($id_client) {$this->id_client = $id_client;}
    public function setStatut($statut) {$this->statut = $statut;}
    public function setMontantTotal($montant_total) {$this->montant_total = $montant_total;}
    public function setAdresseLivraison($adresse_livraison) {$this->adresse_livraison = $adresse_livraison;}
    public function setVilleLivraison($ville_livraison) {$this->ville_livraison = $ville_livraison;}
    public function setCodePostalLivraison($code_postal_livraison) {$this->code_postal_livraison = $code_postal_livraison;}

    public static function getAll() {
        $pdo = Database::getPdo();
        $stmt = $pdo->query("SELECT * FROM commandes ORDER BY id_commande DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByIdCommande($id_commande) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM commandes WHERE id_commande = :id_commande");
        $stmt->bindParam(':id_commande', $id_commande);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByIdClient($id_client) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM commandes WHERE id_client = :id_client ORDER BY id_commande DESC");
        $stmt->bindParam(':id_client', $id_client);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save() {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO commandes (id_client, statut, montant_total, adresse_livraison, ville_livraison, code_postal_livraison)
            VALUES (:id_client, :statut, :montant_total, :adresse_livraison, :ville_livraison, :code_postal_livraison)
        ");
        $stmt->bindValue(':id_client', $this->getIdClient());
        $stmt->bindValue(':statut', $this->getStatut());
        $stmt->bindValue(':montant_total', $this->getMontantTotal());
        $stmt->bindValue(':adresse_livraison', $this->getAdresseLivraison());
        $stmt->bindValue(':ville_livraison', $this->getVilleLivraison());
        $stmt->bindValue(':code_postal_livraison', $this->getCodePostalLivraison());
        return $stmt->execute();
    }

    public function update() {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            UPDATE commandes 
            SET id_client = :id_client,
                statut = :statut,
                montant_total = :montant_total,
                adresse_livraison = :adresse_livraison,
                ville_livraison = :ville_livraison,
                code_postal_livraison = :code_postal_livraison
            WHERE id_commande = :id_commande
        ");
        $stmt->bindValue(':id_client', $this->getIdClient());
        $stmt->bindValue(':statut', $this->getStatut());
        $stmt->bindValue(':montant_total', $this->getMontantTotal());
        $stmt->bindValue(':adresse_livraison', $this->getAdresseLivraison());
        $stmt->bindValue(':ville_livraison', $this->getVilleLivraison());
        $stmt->bindValue(':code_postal_livraison', $this->getCodePostalLivraison());

        return $stmt->execute();
    }

    public static function delete($id_commande) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM commandes WHERE id_commande = :id_commande");
        $stmt->bindValue(':id_commande', $id_commande);
        return $stmt->execute();
    }
}