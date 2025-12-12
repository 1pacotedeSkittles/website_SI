<?php
session_start();

// Destruir todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redirecionar para a página inicial (pré-login)
header("Location: login.php"); // Redireciona para o login ou index.php
exit;
?>