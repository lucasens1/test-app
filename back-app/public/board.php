<?php
include_once '../src/database.php';
include_once '../src/board.php';

$config = include_once '../config/config.php';

try {
    $db = new Database($config);
    $pdo = $db->getConnection();
    $board = new Board($pdo);

    // Richieste HTTP
    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    // POST, GET, PUT, DELETE
    switch ($method) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $board->createBoard($data['name'], $data['description']);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'Board created']);
            break;

        case 'GET':
            if (isset($_GET['user_id'])) {
                $userId = $_GET['user_id'];
                $boards = $board->getBoardsForUser($userId);
                header('Content-Type: application/json');
                echo json_encode($boards);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'User ID is required']);
            }
            break;

        case 'PUT':
            parse_str(file_get_contents("php://input"), $data);
            if (isset($data['id'], $data['name'], $data['description'])) {
                $board->updateBoard($data['id'], $data['name'], $data['description']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Board updated']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid data']);
            }
            break;

        case 'DELETE':
            parse_str(file_get_contents("php://input"), $data);
            if (isset($data['id'])) {
                $board->deleteBoard($data['id']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Board deleted']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'ID is required']);
            }
            break;

        default:
            header('Content-Type: application/json', true, 405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
} catch (PDOException $e) {
    echo 'Errore: ' . $e->getMessage();
}