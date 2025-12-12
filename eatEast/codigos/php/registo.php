<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registo Cliente - eatEasy</title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/login_registo.css">
</head>
<body>

<header>
    <a href="pag_inicial_cliente.php" class="logo">eatEasy</a>
</header>

<main>
    <div class="form-container">
        <div class="auth-form">
            <h2>Registo de Cliente</h2>

            <?php
            // Mensagem de erro de validação ou DB
            if (isset($_SESSION['registo_error'])) {
                echo '<p style="color: red;">' . $_SESSION['registo_error'] . '</p>';
                unset($_SESSION['registo_error']);
            }
            ?>

            <form action="registo_processa.php" method="POST">

                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>

                <button type="submit">Registar</button>
            </form>

            <p>Já tem conta? <a href="login.php">Faça login aqui</a></p>
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