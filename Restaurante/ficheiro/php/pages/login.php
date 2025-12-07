<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Viewport !-->
    <link rel="stylesheet" href="https://use.typekit.net/zcu4bcg.css"> <!-- Font !-->
    <title>eatEasy</title>
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>
<body>

<?php
require '../includes/navbar.php';
?>

<main>
    <div class="Login">
        <div class="logo">eatEasy</div>

        <div class="login-box">
            <h1 class="login-title">Login</h1>
            <form action="#" method="POST" class="login-form">

                <input type="text" name="nome/id" placeholder="Nome/ID" required class="input-field">

                <input type="password" name="password" placeholder="Palavra-Passe" required class="input-field">

                <div class="create-account-link-container">
                    <a href="#" class="create-account-link">Criar conta</a>
                </div>

                <div id="botao-entrar">
                    <a href="posLoginCliente.html" class="entrar">Entrar</a>
                </div>

            </form>
        </div>
    </div>
</main>

<footer class="login-para-admin">
    <a href="login-admin.html" class="login-admin">Administrador</a>
</footer>

</body>
</html>
