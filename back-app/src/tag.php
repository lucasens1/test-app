<?php

class Tag {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTag($name) {
        $stmt = $this->pdo->prepare("INSERT INTO tags (name) VALUES (?)");
        $stmt->execute([$name]);
    }

    public function getTagById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tags WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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

    public function updateTag($id, $name) {
        $stmt = $this->pdo->prepare("UPDATE tags SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
    }

    public function deleteTag($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tags WHERE id = ?");
        $stmt->execute([$id]);
    }
}