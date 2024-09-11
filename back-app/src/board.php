<?php

class Board {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createBoard($name, $description, $userId) {
        try {
            // Inizia una transazione
            $this->pdo->beginTransaction();
    
            // Inserisci la board
            $stmt = $this->pdo->prepare("INSERT INTO boards (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
            $boardId = $this->pdo->lastInsertId(); // Ottieni l'ID dell'ultimo inserimento
    
            // Associa la board all'utente come owner (ruolo con ID 1)
            $roleId = 1; // ID per owner
            $stmt = $this->pdo->prepare("INSERT INTO user_board (user_id, board_id, role_id) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $boardId, $roleId]);
    
            // Commit della transazione
            $this->pdo->commit();
        } catch (PDOException $e) {
            // Rollback in caso di errore
            $this->pdo->rollBack();
            throw $e;
        }
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
    public function getBoardOwner($boardId) {
        // Supponiamo che la tabella user_board contenga un campo "role_id" che identifica il proprietario
        // ad esempio, role_id = 1 indica il proprietario della board
        $stmt = $this->pdo->prepare("
            SELECT user_id 
            FROM user_board 
            WHERE board_id = ? AND role_id = 1
        ");
        $stmt->execute([$boardId]);
    
        // Restituisce l'ID dell'utente proprietario o false se non trovato
        return $stmt->fetchColumn();
    }

    // Metodi per Collaborazione
    public function addUserToBoard($boardId, $userId) {
        $stmt = $this->pdo->prepare("INSERT INTO user_board (user_id, board_id) VALUES (?, ?)");
        $stmt->execute([$userId, $boardId]);
    }

    public function removeUserFromBoard($boardId, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM user_board WHERE user_id = ? AND board_id = ?");
        $stmt->execute([$userId, $boardId]);
    }
}

