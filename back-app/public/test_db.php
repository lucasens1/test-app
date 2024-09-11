<?php
include_once '../src/database.php';
$config = include_once '../config/config.php';

try {
    $db = new Database($config);
    $pdo = $db->getConnection();
    echo 'Connessione al database avvenuta con successo!';
} catch (Exception $e) {
    echo 'Errore: ' . $e->getMessage();
}