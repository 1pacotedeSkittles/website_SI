<?php
// 1. INICIAR SESSÃO para aceder a variáveis de sessão (como mensagens de erro)
session_start();

// Caminho ajustado: voltar um nível (../) para 'php/', depois entrar em 'includes/'
require '../includes/navbar.php';
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

<?php
// A navbar já foi incluída no topo do ficheiro (require '../includes/navbar.php';)
?>

<main>
    <div class="Login">
        <div class="logo">eatEasy</div>

        <div class="login-box">
            <h1 class="login-title">Login</h1>

            <?php
            if (isset($_SESSION['login_erro'])) {
                // Se houver erro após o processamento, exibe-o
                echo '<p style="color: red; text-align: center; margin-bottom: 15px;">' . $_SESSION['login_erro'] . '</p>';
                unset($_SESSION['login_erro']); // Limpa a variável após exibição
            }
            ?>

            <form action="../action/processa.login.php" method="POST" class="login-form">

                <input type="text" name="nome_ou_email" placeholder="Nome/ID ou Email" required class="input-field">

                <input type="password" name="password" placeholder="Palavra-Passe" required class="input-field">

                <div class="create-account-link-container">
                    <a href="loginRegisto.php" class="create-account-link">Criar conta</a>
                </div>

                <div id="botao-entrar">
                    <button type="submit" class="entrar">Entrar</button>
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