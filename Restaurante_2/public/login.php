<?php
session_start();

$title = 'eateasy';
$content = '../src/views/index.view.php';

// Se já está logado, redirecionar
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
require '../includes/master.php';
?>