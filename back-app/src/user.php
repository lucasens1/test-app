<?php
class User{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser($username, $email, $password){
        // Preparo la query
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?,?,?)");
        // Esegue la query
        $stmt->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT)]);
    }

    public function getUserById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUsernmae($username){
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $username, $email) {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $id]);
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Metodo per effettuare il login
    public function login($email, $password) {
        // Verifica che $pdo sia inizializzato
        if ($this->pdo === null) {
            throw new Exception('Database connection not initialized.');
        }

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se l'utente è stato trovato e se la password è corretta
        if ($user && isset($user['password_hash'])) {
            if(password_verify($password, $user['password_hash'])){
                return $user;
            }
        } else {
            return false;
        }
    }
}