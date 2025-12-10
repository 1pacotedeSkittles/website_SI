<!-- Formulário de login -->
<form action="../controllers/logincontroller.php" method="POST" class="login-form">

    <input type="text" name="nome_ou_email" placeholder="Nome/ID ou Email" required class="input-field">

    <input type="password" name="password" placeholder="Palavra-Passe" required class="input-field">

    <div class="create-account-link-container">
        <a href="Registo.php" class="create-account-link">Criar conta</a>
    </div>

    <div id="botao-entrar">
        <button type="submit" class="entrar">Entrar</button>
    </div>

</form>
