<?php
// Mostrar mensagem de erro se houver
if (isset($_GET['erro'])) {
    echo '<p style="color: red;">Credenciais inválidas! Tenta novamente.</p>';
}
?>

<!-- Formulário de login -->
<form action="../src/controllers/logincontroller.php" method="POST" class="login-form">
    <input class="input-field" type="text" name="email" placeholder="email" required>
    <input class="input-field" type="password" name="password" placeholder="Palavra-Passe" required>

    <div class="aux-links">
        <a href="loginRegisto.php">Criar conta</a>
    </div>

    <button class="action-btn" type="submit">Entrar</button>
</form>
