<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>eatEasy</title>
</head>
<body>
<header>
    <?php
    require '../includes/navbar.php';
    ?>
</header>

<main>
    <div class="Login-Admin">
        <div class="logo">eatEasy</div>

        <div class="login-box">
            <h1 class="login-title">Login</h1>
            <form action="#" method="POST" class="login-form">sdad

                <input type="text" name="nome" placeholder="Nome" required class="input-field">

                <input type="password" name="password" placeholder="Palavra-Passe" required class="input-field">

                <div class="create-account-link-container">
                    <a href="#" class="create-account-link">Criar conta</a>
                </div>

                <div id="botao-entrar">
                    <a href="inicialAdmin.html" class="entrar">Entrar</a>
                </div>

            </form>
        </div>
    </div>
</main>

<footer class="login-para-admin">
    <a href="#" class="admin-link">Administrador</a>
</footer>

</body>
</html>