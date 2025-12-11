<?php
session_start();

// Se já está logado, redirecionar
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

include '../src/views/login.view.php';
?>