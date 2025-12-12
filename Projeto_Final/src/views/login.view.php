<main>
    <div class="auth-container">
        <div class="auth-box">
            <h1 class="auth-title">Login</h1>

            <form action="../src/controllers/logincontroller.php" method="POST" class="login-form">
                <input class="input-field" type="username_ou_email" name="username_ou_email" placeholder="username_ou_email" required>
                <input class="input-field" type="password" name="password" placeholder="Palavra-Passe" required>

                <div class="aux-links">
                    <a href="loginRegisto.php">Criar conta</a>
                </div>

                <button class="action-btn" type="submit">Entrar</button>
            </form>

        </div>
    </div>
</main>
