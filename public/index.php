<?php

require '../vendor/autoload.php';

use App\Config;
use App\UserController;
use App\EmailController;
use App\OAuth2;

Config::load();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$oauth2 = new OAuth2();

switch ($uri) {
    case '/register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController = new UserController();
            $data = json_decode(file_get_contents('php://input'), true);
            $userController->register($data['username'], $data['password']);
            echo json_encode(['status' => 'success']);
        }
        break;
    case '/login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController = new UserController();
            $data = json_decode(file_get_contents('php://input'), true);
            $userController->login($data['username'], $data['password']);
        }
        break;
    case '/auth/callback':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $code = $_GET['code'] ?? null;
            if ($code) {
                $authResult = $oauth2->authenticate($code);
                if ($authResult) {
                    // Optionally, store user info in session or database
                    echo json_encode($authResult);
                } else {
                    echo json_encode(['error' => 'Authentication failed']);
                }
            } else {
                echo json_encode(['error' => 'No code provided']);
            }
        }
        break;
    case '/send-email':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($oauth2->isAuthenticated()) {
                $emailController = new EmailController();
                $data = json_decode(file_get_contents('php://input'), true);
                $emailController->sendEmail($data['to'], $data['subject'], $data['body']);
                echo json_encode(['status' => 'queued']);
            } else {
                echo json_encode(['error' => 'Unauthorized']);
            }
        }
        break;
    case '/logout':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oauth2->logout();
            echo json_encode(['status' => 'logged out']);
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;

    case '/auth/callback':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $code = $_GET['code'] ?? null;
            if ($code) {
                $authResult = $oauth2->authenticate($code);
                if ($authResult) {
                    // Optionally, store user info or token
                    echo json_encode($authResult);
                } else {
                    echo json_encode(['error' => 'Authentication failed']);
                }
            } else {
                echo json_encode(['error' => 'No code provided']);
            }
        }
        break;
}
