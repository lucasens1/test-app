<?php
session_start();

include_once '../src/database.php';
include_once '../src/board.php';

$config = include_once '../config/config.php';

try {
    // Verifica che l'utente sia autenticato
    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json', true, 401);
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    $db = new Database($config);
    $pdo = $db->getConnection();
    $board = new Board($pdo);
    $userId = $_SESSION['user_id'];  // L'utente loggato

    // Gestione delle richieste HTTP
    $uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['name'], $data['description'])) {
                $board->createBoard($data['name'], $data['description'], $userId); // Assicurati che $userId sia correttamente definito
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Board created']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid data']);
            }
            break;

        case 'GET':
            if (isset($_GET['user_id'])) {
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
                $boardOwner = $board->getBoardOwner($data['id']);
                if ($boardOwner === $userId) {
                    $board->updateBoard($data['id'], $data['name'], $data['description']);
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'Board updated']);
                } else {
                    header('Content-Type: application/json', true, 403);
                    echo json_encode(['error' => 'Unauthorized action']);
                }
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid data']);
            }
            break;

        case 'DELETE':
            parse_str(file_get_contents("php://input"), $data);
            if (isset($data['id'])) {
                $boardOwner = $board->getBoardOwner($data['id']);
                if ($boardOwner === $userId) {
                    $board->deleteBoard($data['id']);
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'Board deleted']);
                } else {
                    header('Content-Type: application/json', true, 403);
                    echo json_encode(['error' => 'Unauthorized action']);
                }
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
