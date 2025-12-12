<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo Cliente - eatEasy</title>
</head>
<body>
<h2>Registar Novo Cliente</h2>

<?php
// Exibe mensagens de erro ou sucesso
if (isset($_SESSION['registo_mensagem'])) {
    // Assume que a mensagem de sucesso é verde, e de erro é vermelha
    $cor = str_contains($_SESSION['registo_mensagem'], '✅') ? 'green' : 'red';
    echo '<p style="color: ' . $cor . ';">' . $_SESSION['registo_mensagem'] . '</p>';
    unset($_SESSION['registo_mensagem']);
}
?>

<form action="registo_processa.php" method="POST">
    <label for="nome">Nome Completo:</label>
    <input type="text" id="nome" name="nome" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Registar</button>
</form>
<p>Já tem conta? <a href="login.php">Faça Login aqui</a></p>
</body>
</html>