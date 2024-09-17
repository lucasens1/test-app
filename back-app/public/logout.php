<?php 
include_once '../src/cors.php';
session_start();
session_unset();
session_destroy();
echo json_encode(['status' => 'Logout Ok!']);
?>