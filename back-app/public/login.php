<?php
session_start();
include_once '../src/database.php';
include_once '../src/user.php';

$config = include_once '../config/config.php';

try {
    $db = new Database($config);
    $pdo = $db->getConnection();
    $user = new User($pdo);

    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['email']) && isset($data['password'])) {
        $loggedInUser = $user->login($data['email'], $data['password']);

        if ($loggedInUser) {
            $_SESSION['user_id'] = $loggedInUser['id'];
            echo json_encode(['status' => 'Login successful']);
        } else {
            header('Content-Type: application/json', true, 401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
    } else {
        header('Content-Type: application/json', true, 400);
        echo json_encode(['error' => 'Email and password required']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json', true, 500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>