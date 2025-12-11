<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.typekit.net/zcu4bcg.css">
    <title>eatEasy</title>

    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>
<body>

<header>
    <?php include '../includes/navbar.php'; ?>
</header>

<main>
    <div class="auth-container">
        <div class="auth-box">
            <h1 class="auth-title">Login</h1>

            <?php if(isset($_SESSION['login_erro'])): ?>
                <div class="msg-error"><?php echo $_SESSION['login_erro']; unset($_SESSION['login_erro']); ?></div>
            <?php endif; ?>

            <form action="../src/controllers/logincontroller.php" method="POST" class="login-form">
                <input class="input-field" type="email" name="email" placeholder="email" required>
                <input class="input-field" type="password" name="password" placeholder="Palavra-Passe" required>

                <div class="aux-links">
                    <a href="loginRegisto.php">Criar conta</a>
                </div>

                <button class="action-btn" type="submit">Entrar</button>
            </form>

            <div class="auth-footer">
                <a href="../pages/loginAdmin.php">Administrador</a>
            </div>
        </div>
    </div>
</main>

</body>
</html>
