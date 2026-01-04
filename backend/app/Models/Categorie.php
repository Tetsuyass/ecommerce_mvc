<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Categorie {
    private $id_categorie;
    private $nom_categorie;
    private $description_categorie;
    private $image_categorie;

    public function getIdCategorie() {return $this->id_categorie;}
    public function getNomCategorie() {return $this->nom_categorie;}
    public function getDescriptionCategorie() {return $this->description_categorie;}
    public function getImageCategorie() {return $this->image_categorie;}
    public function setIdCategorie($id_categorie) {$this->id_categorie = $id_categorie;}
    public function setNomCategorie($nom_categorie) {$this->nom_categorie = $nom_categorie;}
    public function setDescriptionCategorie($description_categorie) {$this->description_categorie = $description_categorie;}
    public function setImageCategorie($image_categorie) {$this->image_categorie = $image_categorie;}

    public static function getAll() {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("SELECT * FROM categorie ORDER BY id_categorie DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id_categorie) {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("SELECT * FROM categorie WHERE id_categorie = :id_categorie");
        $stmt->bindParam(":id_categorie", $id_categorie);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save() {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("
            INSERT INTO categorie (nom_categorie, description_categorie, image_categorie)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([
            $this->getNomCategorie(),
            $this->getDescriptionCategorie(),
            $this->getImageCategorie()
        ]);
    }

    public function update() {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("
            UPDATE categorie
            SET nom_categorie = ?, description_categorie = ?, image_categorie = ?
            WHERE id_categorie = ?
        ");
        return $stmt->execute([
            $this->getNomCategorie(),
            $this->getDescriptionCategorie(),
            $this->getImageCategorie(),
            $this->getIdCategorie()
        ]);
    }

    public static function delete($id_categorie) {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare("DELETE FROM categorie WHERE id_categorie = ?");
        $stmt->bindParam(":id_categorie", $id_categorie);
        return $stmt->execute();
    }
}
