<?php
require_once '../../config/database.php';
$connection=getDBConnection();
$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username = $1";
$result = pg_query_params($connection, $query, array($username));
$user = pg_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    session_start();
    $_SESSION['user_id'] = $user['id'];
    header('Location: index.php');
} else {
    echo "Credenciais inválidas";
}