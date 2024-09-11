<?php

class Board {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createBoard($name, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO boards (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
    }

    public function getBoardById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM boards WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBoardsForUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT b.* 
            FROM boards b 
            JOIN user_board ub ON b.id = ub.board_id 
            WHERE ub.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBoard($id, $name, $description) {
        $stmt = $this->pdo->prepare("UPDATE boards SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $id]);
    }

    public function deleteBoard($id) {
        $stmt = $this->pdo->prepare("DELETE FROM boards WHERE id = ?");
        $stmt->execute([$id]);
    }
}