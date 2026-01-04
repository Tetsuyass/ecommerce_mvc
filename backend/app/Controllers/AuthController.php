<?php

namespace App\Controllers;

use App\Models\Client;
use App\Models\Admins;

class AuthController
{
    /**
     * POST /auth/register
     * Inscription client
     */
    public function register()
    {
        header('Content-Type: application/json');
        session_start();

        $data = json_decode(file_get_contents('php://input'), true);

        if (
            empty($data['email']) ||
            empty($data['password']) ||
            empty($data['adresse']) ||
            empty($data['ville']) ||
            empty($data['code_postal'])
        ) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Champs manquants'
            ]);
            return;
        }

        // Vérifier email existant
        if (Client::findByEmail($data['email'])) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'Email déjà utilisé'
            ]);
            return;
        }

        $client = new Client();
        $client->setEmailClient($data['email']);
        $client->setMdpClient(password_hash($data['password'], PASSWORD_DEFAULT));
        $client->setAdresse($data['adresse']);
        $client->setVille($data['ville']);
        $client->setCodePostal($data['code_postal']);
        $client->save();

        echo json_encode([
            'success' => true,
            'message' => 'Compte créé'
        ]);
    }

    /**
     * POST /auth/login
     * Connexion client
     */
    public function login()
    {
        header('Content-Type: application/json');
        session_start();

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Email ou mot de passe manquant'
            ]);
            return;
        }

        $client = Client::findByEmail($data['email']);

        if (!$client || !password_verify($data['password'], $client['mdp_client'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Identifiants invalides'
            ]);
            return;
        }

        $_SESSION['client_id'] = $client['id_client'];
        $_SESSION['role'] = 'client';

        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie'
        ]);
    }

    /**
     * POST /auth/admin/login
     * Connexion admin
     */
    public function adminLogin()
    {
        header('Content-Type: application/json');
        session_start();

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Champs manquants'
            ]);
            return;
        }

        $admin = Admins::getAll();
        $adminFound = null;

        foreach ($admin as $a) {
            if ($a['email'] === $data['email']) {
                $adminFound = $a;
                break;
            }
        }

        if (!$adminFound || !password_verify($data['password'], $adminFound['mdp'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Identifiants invalides'
            ]);
            return;
        }

        $_SESSION['admin_id'] = $adminFound['id_admin'];
        $_SESSION['role'] = $adminFound['role'];

        echo json_encode([
            'success' => true,
            'message' => 'Connexion admin réussie'
        ]);
    }

    /**
     * POST /auth/logout
     * Déconnexion
     */
    public function logout()
    {
        header('Content-Type: application/json');
        session_start();

        session_destroy();

        echo json_encode([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * GET /auth/me
     * Utilisateur connecté
     */
    public function me()
    {
        header('Content-Type: application/json');
        session_start();

        if (isset($_SESSION['client_id'])) {
            $client = Client::findById($_SESSION['client_id']);

            echo json_encode([
                'success' => true,
                'data' => [
                    'type' => 'client',
                    'user' => $client
                ]
            ]);
            return;
        }

        if (isset($_SESSION['admin_id'])) {
            $admin = Admins::findByIdAmin($_SESSION['admin_id']);

            echo json_encode([
                'success' => true,
                'data' => [
                    'type' => 'admin',
                    'user' => $admin
                ]
            ]);
            return;
        }

        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Non authentifié'
        ]);
    }
}
