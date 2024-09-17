<?php
session_start();
include_once '../src/cors.php';
include_once '../src/database.php';
include_once '../src/tag.php';

$config = include_once '../config/config.php';

try {
    // Verifica che l'utente sia autenticato
    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json', true, 401);
        echo json_encode(['error' => 'Non autorizzato']);
        exit();
    }

    // Connetti al database
    $db = new Database($config);
    $pdo = $db->getConnection();
    $tag = new Tag($pdo);

    // Recupera il metodo HTTP
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'POST':
            // Creazione di un nuovo tag
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['name'])) {
                $tag->createTag($data['name']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Tag creato']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Dati non validi']);
            }
            break;

        case 'GET':
            // Lettura dei tag
            if (isset($_GET['id'])) {
                // Leggi un tag specifico tramite ID
                $tagData = $tag->getTagById($_GET['id']);
                header('Content-Type: application/json');
                echo json_encode($tagData);
            } elseif (isset($_GET['todo_list_id'])) {
                // Leggi tutti i tag associati a una to-do list
                $tags = $tag->getTagsForTodoList($_GET['todo_list_id']);
                header('Content-Type: application/json');
                echo json_encode($tags);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Richiesta invalida']);
            }
            break;

        case 'PUT':
            // Aggiornamento di un tag
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'], $data['name'])) {
                $tag->updateTag($data['id'], $data['name']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Tag aggiornato']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Dati non validi']);
            }
            break;

        case 'DELETE':
            // Cancellazione di un tag
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) {
                $tag->deleteTag($data['id']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Tag eliminato']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Dati non validi']);
            }
            break;

        default:
            header('Content-Type: application/json', true, 405);
            echo json_encode(['error' => 'Non permesso']);
            break;
    }
} catch (PDOException $e) {
    echo 'Errore: ' . $e->getMessage();
}
?>