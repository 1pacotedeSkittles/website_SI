<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eatEasy - Login Admin</title>

    <link rel="stylesheet" href="https://use.typekit.net/zcu4bcg.css">

    <!-- CSS base igual ao login do cliente -->
    <link rel="stylesheet" href="../../css/loginAdmin.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>

<header>
    <?php require '../includes/navbar.php'; ?>
</header>

<main>
    <div class="auth-container">
        <div class="auth-box">
            <h1 class="auth-title">Login Admin</h1>

            <?php if(isset($_SESSION['admin_erro'])): ?>
                <div class="msg-error"><?php echo $_SESSION['admin_erro']; unset($_SESSION['admin_erro']); ?></div>
            <?php endif; ?>

            <form class="auth-form" action="processa.login.admin.php" method="POST">
                <input class="input-field" type="text" name="id_trabalho" placeholder="ID de Trabalho" required>
                <input class="input-field" type="password" name="password_admin" placeholder="Palavra-Passe" required>

                <!-- Não mostrar link criar conta para admin -->
                <button class="action-btn" type="submit">Entrar</button>
            </form>
        </div>
    </div>
</main>


</body>
</html>