<?php

//Le router principal du backend.
//TODO: Finir les modèles

ini_set('display_errors', 1);
error_reporting(E_ALL);

// CORS
header("Access-Control-Allow-Origin: http://localhost:5174");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Expose-Headers: Set-Cookie");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// autoload maison pour remplacer composer
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

use App\Controllers\ProductController;
use App\Controllers\CartController;
use App\Controllers\OrderController;
use App\Controllers\AuthController;

// toujours JSON
header('Content-Type: application/json');

// Nettoyage de l’URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Retirer /index.php si présent
$uri = str_replace('/index.php', '', $uri);

// Découper l’URL
$segments = array_values(array_filter(explode('/', $uri)));

// Exemple : /products/3
$resource = $segments[0] ?? null;
$id = $segments[1] ?? null;

/**
 * ============================
 * ROUTES PRODUITS
 * ============================
 */
if ($resource === 'products') {

    $controller = new ProductController();

    if ($method === 'GET' && !$id) {
        $controller->index();
        exit;
    }

    if ($method === 'GET' && $id) {
        $controller->show($id);
        exit;
    }


    if ($method === 'DELETE' && $id) {
        $controller->delete($id);
        exit;
    }
}

/**
 * ============================
 * ROUTES PANIER
 * ============================
 */
if ($resource === 'cart') {

    $controller = new CartController();

    if ($method === 'GET') {
        $controller->index();
        exit;
    }

    if ($method === 'POST' && ($id === 'add')) {
        $controller->add();
        exit;
    }

    if ($method === 'POST' && ($id === 'update')) {
        $controller->update();
        exit;
    }

    if ($method === 'POST' && ($id === 'remove')) {
        $controller->remove();
        exit;
    }

    if ($method === 'POST' && ($id === 'clear')) {
        $controller->clear();
        exit;
    }
}

/**
 * ============================
 * ROUTES COMMANDES
 * ============================
 */
if ($resource === 'orders') {

    $controller = new OrderController();

    if ($method === 'POST') {
        $controller->store();
        exit;
    }

    if ($method === 'GET' && !$id) {
        $controller->index();
        exit;
    }

    if ($method === 'GET' && $id) {
        $controller->show($id);
        exit;
    }
}

/**
 * ============================
 * ROUTES AUTH
 * ============================
 */
if ($resource === 'auth') {

    $controller = new AuthController();
    $action = $segments[1] ?? null;

    if ($method === 'POST' && $action === 'register') {
        $controller->register();
        exit;
    }

    if ($method === 'POST' && $action === 'login') {
        $controller->login();
        exit;
    }

    if ($method === 'POST' && $action === 'logout') {
        $controller->logout();
        exit;
    }

    if ($method === 'POST' && $action === 'admin' && ($segments[2] ?? null) === 'login') {
        $controller->adminLogin();
        exit;
    }

    if ($method === 'GET' && $action === 'me') {
        $controller->me();
        exit;
    }
}

/**
 * ============================
 * GESTION 404
 * ============================
 */
http_response_code(404);
echo json_encode([
    'success' => false,
    'message' => 'Route introuvable'
]);