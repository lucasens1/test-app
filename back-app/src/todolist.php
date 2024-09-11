<?php

class TodoList {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTodoList($boardId, $title) {
        $stmt = $this->pdo->prepare("INSERT INTO todo_lists (board_id, title) VALUES (?, ?)");
        $stmt->execute([$boardId, $title]);
    }

    public function getTodoListById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM todo_lists WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTodoListsForBoard($boardId) {
        $stmt = $this->pdo->prepare("SELECT * FROM todo_lists WHERE board_id = ?");
        $stmt->execute([$boardId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTodoList($id, $title) {
        $stmt = $this->pdo->prepare("UPDATE todo_lists SET title = ? WHERE id = ?");
        $stmt->execute([$title, $id]);
    }

    public function deleteTodoList($id) {
        $stmt = $this->pdo->prepare("DELETE FROM todo_lists WHERE id = ?");
        $stmt->execute([$id]);
    }
}