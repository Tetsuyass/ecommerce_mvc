<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Client {
    private $id_client;
    private $adresse;
    private $ville;
    private $code_postal;
    private $email_client;
    private $mdp_client;

    public function getIdClient() {return $this->id_client;}
    public function getAdresse() {return $this->adresse;}
    public function getVille() {return $this->ville;}
    public function getCodePostal() {return $this->code_postal;}
    public function getEmailClient() {return $this->email_client;}
    public function getMdpClient() {return $this->mdp_client;}
    public function setIdClient($id_client) {$this->id_client = $id_client;}
    public function setAdresse($adresse) {$this->adresse = $adresse;}
    public function setVille($ville) {$this->ville = $ville;}
    public function setCodePostal($code_postal) {$this->code_postal = $code_postal;}
    public function setEmailClient($email_client) {$this->email_client = $email_client;}
    public function setMdpClient($mdp_client) {$this->mdp_client = $mdp_client;}

    public static function getAll() {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM client ORDER BY id_client DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id_client) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM client WHERE id_client = :id_client");
        $stmt->bindParam(":id_client", $id_client);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function  findByEmail($email_client) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM client WHERE email_client = :email_client");
        $stmt->bindParam(":email_client", $email_client);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save() {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO client (adresse, ville, code_postal, email_client, mdp_client)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $this->getAdresse(),
            $this->getVille(),
            $this->getCodePostal(),
            $this->getEmailClient(),
            $this->getMdpClient(),
        ]);
    }

    public function update() {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            UPDATE client
            SET adresse = ?,
                ville = ?,
                code_postal = ?,
                email_client = ?,
                mdp_client = ?
            WHERE id_client = ?
        ");

        return $stmt->execute([
            $this->getAdresse(),
            $this->getVille(),
            $this->getCodePostal(),
            $this->getEmailClient(),
            $this->getMdpClient(),
            $this->getIdClient()
        ]);
    }

    public static function delete($id_client) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM client WHERE id_client = :id_client");
        $stmt->bindParam(":id_client", $id_client);
        return $stmt->execute();
    }

}