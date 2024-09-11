<?php

class UserBoard {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Assegna un ruolo 
    public function assignRoleToUser($userId, $boardId, $role) {
        $stmt = $this->pdo->prepare("INSERT INTO user_board (user_id, board_id, role) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE role = ?");
        $stmt->execute([$userId, $boardId, $role, $role]);
    }

    public function getRoleForUserOnBoard($userId, $boardId) {
        $stmt = $this->pdo->prepare("SELECT role FROM user_board WHERE user_id = ? AND board_id = ?");
        $stmt->execute([$userId, $boardId]);
        return $stmt->fetchColumn();
    }

    // Rimuove user
    public function removeUserFromBoard($userId, $boardId) {
        $stmt = $this->pdo->prepare("DELETE FROM user_board WHERE user_id = ? AND board_id = ?");
        $stmt->execute([$userId, $boardId]);
    }
}