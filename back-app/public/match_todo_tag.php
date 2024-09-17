<?php
session_start();
include_once '../src/cors.php';
include_once '../src/database.php';
include_once '../src/todolist_tag.php';

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
    $todoListTag = new TodoListTag($pdo);

    // Recupera il metodo HTTP
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'POST':
            // Aggiunge un tag a una to-do list
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['todo_list_id'], $data['tag_id'])) {
                $todoListTag->addTagToTodoList($data['todo_list_id'], $data['tag_id']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Tag added to to-do list']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid data']);
            }
            break;

        case 'DELETE':
            // Rimuove un tag da una to-do list
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['todo_list_id'], $data['tag_id'])) {
                $todoListTag->removeTagFromTodoList($data['todo_list_id'], $data['tag_id']);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'Tag removed from to-do list']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid data']);
            }
            break;

        case 'GET':
            // Ottieni tutti i tag associati a una to-do list
            if (isset($_GET['todo_list_id'])) {
                $tags = $todoListTag->getTagsForTodoList($_GET['todo_list_id']);
                header('Content-Type: application/json');
                echo json_encode($tags);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Invalid request']);
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