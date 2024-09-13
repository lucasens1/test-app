<?php

class TodoListTag {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Associa un tag a una to-do list
    public function addTagToTodoList($todoListId, $tagId) {
        $stmt = $this->pdo->prepare("INSERT INTO todo_list_tag (todo_list_id, tag_id) VALUES (?, ?)");
        $stmt->execute([$todoListId, $tagId]);
    }

    // Rimuove un tag da una to-do list
    public function removeTagFromTodoList($todoListId, $tagId) {
        $stmt = $this->pdo->prepare("DELETE FROM todo_list_tag WHERE todo_list_id = ? AND tag_id = ?");
        $stmt->execute([$todoListId, $tagId]);
    }

    // Leggi tutti i tag associati a una to-do list
    public function getTagsForTodoList($todoListId) {
        $stmt = $this->pdo->prepare("
            SELECT t.* 
            FROM tags t 
            JOIN todo_list_tag tlt ON t.id = tlt.tag_id 
            WHERE tlt.todo_list_id = ?
        ");
        $stmt->execute([$todoListId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Rimuovi tutti i tag associati a una to-do list (opzionale)
    public function removeAllTagsFromTodoList($todoListId) {
        $stmt = $this->pdo->prepare("DELETE FROM todo_list_tag WHERE todo_list_id = ?");
        $stmt->execute([$todoListId]);
    }
}