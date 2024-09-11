<?php
session_start();


include_once '../src/database.php';
include_once '../src/board.php';

$config = include_once '../config/config.php';

try{
    // Verifico user logged
    if(!isset($_SESSION['user_id'])){
        header('Content-Type: application/json', true, 401);
        echo json_encode(['error' => 'Non autorizzato']);
        exit();
    }
} catch (PDOException $e){
    echo 'Error : '.$e->getMessage();
}