<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - Restaurante</title>
    <link rel="stylesheet" href="../public/css/login.css">
</head>

<?php
// Mostrar mensagem de erro se houver
if (isset($_GET['erro'])) {
    echo '<p style="color: red;">Credenciais inválidas! Tenta novamente.</p>';
}
?>

<!-- Formulário de login -->
<form action="/Restaurante_2/src/controllers/logincontroller.php" method="POST" class="login-form">

    <input type="text" name="username_ou_email" placeholder="username ou email" required class="input-field">

    <input type="password" name="password" placeholder="palavra-passe" required class="input-field">

    <div class="create-account-link-container">
        <a href="Registo.php" class="create-account-link">Criar conta</a>
    </div>

    <div id="botao-entrar">
        <button type="submit" class="entrar">Entrar</button>
    </div>

</form>
