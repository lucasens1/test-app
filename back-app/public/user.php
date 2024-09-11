<?php
require_once '../src/database.php';
require_once '../src/user.php';

$config = require '../config/config.php';

try {
    $db = new Database($config);
    $pdo = $db->getConnection();
    $user = new User($pdo);

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['username'], $data['email'], $data['password'])) {
                $user->createUser($data['username'], $data['email'], $data['password']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'User created']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid data']);
            }
            break;

        case 'GET':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $userData = $user->getUserById($id);
                if ($userData) {
                    header('Content-Type: application/json');
                    echo json_encode($userData);
                } else {
                    header('Content-Type: application/json', true, 404);
                    echo json_encode(['error' => 'User not found']);
                }
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'User ID is required']);
            }
            break;

        case 'PUT':
            parse_str(file_get_contents("php://input"), $data);
            if (isset($data['id'], $data['username'], $data['email'])) {
                $user->updateUser($data['id'], $data['username'], $data['email']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'User updated']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid data']);
            }
            break;

        case 'DELETE':
            parse_str(file_get_contents("php://input"), $data);
            if (isset($data['id'])) {
                $user->deleteUser($data['id']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'User deleted']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'User ID is required']);
            }
            break;

        default:
            header('Content-Type: application/json', true, 405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
} catch (PDOException $e) {
    header('Content-Type: application/json', true, 500);
    echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
}