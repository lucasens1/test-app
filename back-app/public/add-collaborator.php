<?php
session_start();

include_once '../src/database.php';
include_once '../src/board.php';

$config = include_once '../config/config.php';

try {
    // Verifica che l'utente sia autenticato
    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json', true, 401);
        echo json_encode(['error' => 'Non autorizzato']);
        exit();
    }

    $db = new Database($config);
    $pdo = $db->getConnection();
    $board = new Board($pdo);
    $userId = $_SESSION['user_id'];  // L'utente loggato

    // Gestione delle richieste HTTP
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['board_id'], $data['user_id'])) {
                // Log di debug per i dati ricevuti
                error_log("Dati ricevuti: " . print_r($data, true));
                // Verifica se l'utente loggato è il proprietario della board
                $boardOwner = $board->getBoardOwner($data['board_id']);
                if ($boardOwner === $userId) {
                    // Assegna il ruolo di collaboratore (ID: 2)
                    $stmt = $pdo->prepare("INSERT INTO user_board (user_id, board_id, role_id) VALUES (?, ?, ?)");
                    $stmt->execute([$data['user_id'], $data['board_id'], 2]);
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'Collaborator added']);
                } else {
                    header('Content-Type: application/json', true, 403);
                    echo json_encode(['error' => 'Unauthorized action']);
                }
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid data']);
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
?>