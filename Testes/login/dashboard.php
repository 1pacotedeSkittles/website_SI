<?php
session_start(); // Inicia a sessão

// Verifica se o usuário NÃO está logado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    // Redireciona para a página de login
    header("Location: login.php");
    exit();
}

// O usuário está logado, exibe o conteúdo restrito
?>
<h2>Bem-vindo(a) à sua Área Restrita, <?php echo $_SESSION['usuario_nome']; ?>!</h2>
<p>Este é o conteúdo que só pode ser visto por usuários autenticados.</p>
<p><a href="logout.php">Sair (Logout)</a></p>