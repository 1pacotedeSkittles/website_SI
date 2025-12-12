<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login Cliente - eatEasy</title>
</head>
<body>
<h2>Login de Cliente</h2>

<?php
// Mensagem de sucesso após registo ou falha de login anterior
if (isset($_SESSION['registo_mensagem'])) {
    echo '<p style="color: green;">' . $_SESSION['registo_mensagem'] . '</p>';
    unset($_SESSION['registo_mensagem']);
}
if (isset($_SESSION['login_error'])) {
    echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
    unset($_SESSION['login_error']);
}
?>

<form action="login_processa.php" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<p>Ainda não tem conta? <a href="registo.php">Registe-se aqui</a></p>
<hr>
<p>É um administrador?
    <a href="../php_admin/login_admin.php">Clique aqui para entrar</a>
</p>
</body>
</html>