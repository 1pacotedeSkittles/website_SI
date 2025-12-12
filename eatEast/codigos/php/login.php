<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login Cliente - eatEasy</title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/login_registo.css"> </head>
<body>

<header>
    <a href="pag_inicial_cliente.php" class="logo">eatEasy</a>
    <div class="nav-links">
    </div>
</header>

<main>
    <div class="form-container">
        <div class="auth-form">
            <h2>Login de Cliente</h2>

            <?php
            // Mensagem de sucesso após registo
            if (isset($_SESSION['registo_mensagem'])) {
                echo '<p style="color: green;">' . $_SESSION['registo_mensagem'] . '</p>';
                unset($_SESSION['registo_mensagem']);
            }
            // Mensagem de erro de login
            if (isset($_SESSION['login_error'])) {
                echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
                unset($_SESSION['login_error']);
            }
            ?>

            <form action="../php/login_processa.php" method="POST">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>

                <button type="submit">Login</button>
            </form>

            <p>Ainda não tem conta? <a href="registo.php">Registe-se aqui</a></p>

            <hr style="border-top: 1px solid #eee;">

            <p>É um administrador?
                <a href="../php_admin/login_admin.php">Clique aqui para entrar</a>
            </p>
        </div>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2025 eatEasy. Todos os direitos reservados.</p>
    </div>
</footer>
</body>
</html>