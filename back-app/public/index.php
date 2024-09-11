<?php
require_once '../src/database.php';
require_once '../src/board.php';
require_once '../src/user.php';
require_once '../src/tag.php';
require_once '../src/todolist.php';
// Config
$config = require '../config/config.php';
try {
    // Istanza DB
    $db = new Database($config);
    $pdo = $db->getConnection(); // Provo la connessione

    $stmt = $pdo->query("SELECT 'Conessione stabilita!' AS message");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $result['message'];
} catch(PDOException $e) {
    echo 'Errore di connessione : ' . $e->getMessage();
}