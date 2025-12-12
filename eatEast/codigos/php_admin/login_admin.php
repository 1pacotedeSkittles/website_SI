<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador - eatEasy</title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/login_registo.css">
</head>
<body>

<header>
    <a href="../php/pag_inicial_cliente.php" class="logo">eatEasy</a>
</header>

<main>
    <div class="form-container">
        <div class="auth-form">
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
                <input type="email" id="email" name="email" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>

                <button type="submit">Login Administrador</button>
            </form>

            <p><a href="../php/login.php">← Voltar para o Login de Cliente</a></p>
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