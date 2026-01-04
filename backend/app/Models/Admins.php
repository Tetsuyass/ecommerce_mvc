<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Admins {
    private $id_admin;
    private $username;
    private $email;
    private $mdp;
    private $role;

    public function getIdAdmin() {return $this->id_admin;}
    public function getUsername() {return $this->username;}
    public function getEmail() {return $this->email;}
    public function getMdp() {return $this->mdp;}
    public function getRole() {return $this->role;}
    public function setId_Admin($id_admin) {$this->id_admin = $id_admin;}
    public function setUsername($username) {$this->username = $username;}
    public function setEmail($email) {$this->email = $email;}
    public function setMdp($mdp) {$this->mdp = $mdp;}
    public function setRole($role) {$this->role = $role;}

    public static function getAll() {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM admins ORDER BY id_admin DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByIdAmin($id_admin) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE id_admin = :id_admin");
        $stmt->bindParam(":id_admin", $id_admin);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save() {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO admins (username, email, mdp, role)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $this->getUsername(),
            $this->getEmail(),
            $this->getMdp(),
            $this->getRole()
        ]);
    }

    public function update() {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            UPDATE admins
            SET username = ?,
                email = ?,
                mdp = ?,
                role = ?
            WHERE id_admin = ?
        ");

        return $stmt->execute([
            $this->getUsername(),
            $this->getEmail(),
            $this->getMdp(),
            $this->getRole(),
        ]);
    }

    public static function delete($id_admin) {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM admins WHERE id_admin = :id_admin");
        $stmt->bindParam(":id_admin", $id_admin);
        $stmt->execute();
    }
}