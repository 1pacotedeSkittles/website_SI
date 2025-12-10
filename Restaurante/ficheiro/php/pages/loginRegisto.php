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
    <?php include '../includes/navbar.php'; ?>
</header>

<main>
    <div class="auth-container">
        <div class="auth-box">
            <h1 class="auth-title">Registo</h1>

            <?php if(isset($_SESSION['registo_erro'])): ?>
                <div class="msg-error"><?php echo $_SESSION['registo_erro']; unset($_SESSION['registo_erro']); ?></div>
            <?php endif; ?>

            <form class="auth-form" action="action/processa.registo.php" method="POST">
                <input class="input-field" type="text" name="nome" placeholder="Nome completo" required>
                <input class="input-field" type="email" name="email" placeholder="Email" required>
                <input class="input-field" type="password" name="password" placeholder="Palavra-Passe" required>

                <div class="aux-links">
                    <a href="login.php">Já tenho conta</a>
                </div>

                <button class="action-btn" type="submit">Registar</button>
            </form>
        </div>
    </div>
</main>

<footer class="login-para-admin">
    <a href="loginAdmin.php" class="login-admin">Administrador</a>
</footer>

</body>
</html>

