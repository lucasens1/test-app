<?php
session_start();

include_once '../src/cors.php';
include_once '../src/database.php';
include_once '../src/todolist.php';

$config = include_once '../config/config.php';

try{
    // Verifico user logged
    if(!isset($_SESSION['user_id'])){
        header('Content-Type: application/json', true, 401);
        echo json_encode(['error' => 'Non autorizzato']);
        exit();
    }
    // Connetto DB
    $db = new Database($config);
    // Genero pdo dal DB
    $pdo = $db->getConnection();
    // Genero nuova todo
    $todo = new TodoList($pdo);
    // Prendo l'id dell'utente loggato
    $userId = $_SESSION['user_id'];
    // Salvo il metodo di richiesta al server
    $method = $_SERVER['REQUEST_METHOD'];

    switch($method){
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true); // Decodifica in array associativo Php JSON
            if (isset($data['board_id']) && isset($data['title'])) {
                $boardId = $data['board_id'];
                $title = $data['title'];

                // Creo una nuova todo list
                $todo->createTodoList($boardId, $title);
                header('Content-Type: application/json', true, 201);
                echo json_encode(['message' => 'To-do list creata con successo']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Dati mancanti']);
            }
            break;
        
        case 'GET':
            if (isset($_GET['id'])) {
                $todoId = $_GET['id'];
                $todoList = $todo->getTodoListById($todoId);
                if ($todoList) {
                    header('Content-Type: application/json', true, 200);
                    echo json_encode($todoList);
                } else {
                    header('Content-Type: application/json', true, 404);
                    echo json_encode(['error' => 'To-do list non trovata']);
                }
            } elseif (isset($_GET['board_id'])) {
                $boardId = $_GET['board_id'];
                $todoLists = $todo->getTodoListsForBoard($boardId);
                header('Content-Type: application/json', true, 200);
                echo json_encode($todoLists);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Parametro board_id mancante']);
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id']) && isset($data['title'])) {
                $todoId = $data['id'];
                $title = $data['title'];

                // Aggiorno la to-do list
                $todo->updateTodoList($todoId, $title);
                header('Content-Type: application/json', true, 200);
                echo json_encode(['message' => 'To-do list aggiornata con successo']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Dati mancanti']);
            }
            break;

        case 'DELETE':
            if (isset($_GET['id'])) {
                $todoId = $_GET['id'];

                // Elimino la to-do list
                $todo->deleteTodoList($todoId);
                header('Content-Type: application/json', true, 200);
                echo json_encode(['message' => 'To-do list eliminata con successo']);
            } else {
                header('Content-Type: application/json', true, 400);
                echo json_encode(['error' => 'Parametro id mancante']);
            }
            break;

        default:
            header('Content-Type: application/json', true, 405);
            echo json_encode(['error' => 'Metodo non consentito']);
            break;
    }


} catch (PDOException $e){
    echo 'Error : '.$e->getMessage();
}