<?php

class Role {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ottieni un ruolo per ID
    public function getRoleById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ottieni tutti i ruoli
    public function getAllRoles() {
        $stmt = $this->pdo->query("SELECT * FROM roles");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}