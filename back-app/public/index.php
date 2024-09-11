<?php

$config = require '../config/config.php';
require '../src/database.php';

try {
    // Istanza DB
    $db = new Database($config);
    $pdo = $db->getConnection(); // Provo la connessione

    $stmt = $pdo->query("SELECT 'connection successful!' AS message");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $result['message'];
} catch(PDOException $e) {
    echo 'Errore di connessione : ' . $e->getMessage();
}