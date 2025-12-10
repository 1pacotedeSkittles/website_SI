<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>eatEasy - Registar</title>
    <link rel="stylesheet" href="../../css/loginRegisto.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">
</head>
<body>

<header>
    <nav>
        <?php
        require '../includes/navbar.php';
        ?>
    </nav>
</header>

<main>
    <div class="Registo">
        <div class="logo"></div>

        <div class="registo-box">
            <h1 class="registo-title">Registar Conta</h1>
            <form action="processa.registo.php" method="POST" class="registo-form">

                <input type="text" name="nome" placeholder="Nome Completo" required class="input-field">

                <input type="email" name="email" placeholder="E-mail" required class="input-field">

                <input type="password" name="password" placeholder="Palavra-Passe" required class="input-field">

                <input type="password" name="password_confirmacao" placeholder="Confirmar Palavra-Passe" required class="input-field">

                <div class="login-link-container">
                    <a href="login.php" class="login-link">Já tenho conta</a>
                </div>

                <div id="botao-registar">
                    <button type="submit" class="registar">Criar Conta</button>
                </div>

            </form>
        </div>
    </div>
</main>

<footer class="login-para-admin">
    <a href="loginAdmin.php" class="login-admin">Administrador</a>
</footer>

</body>
</html>

