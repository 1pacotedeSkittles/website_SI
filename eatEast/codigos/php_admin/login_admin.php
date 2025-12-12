<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador - eatEasy</title>
</head>
<body>
<h2>Login de Administrador</h2>

<?php
// Exibe mensagens de erro
if (isset($_SESSION['admin_login_error'])) {
    echo '<p style="color: red;">' . $_SESSION['admin_login_error'] . '</p>';
    unset($_SESSION['admin_login_error']);
}
?>

<form action="processa_login_admin.php" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login Administrador</button>
</form>

<p><a href="../php/login.php">← Voltar para o Login de Cliente</a></p>
</body>
</html>