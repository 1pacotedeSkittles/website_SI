<h2>Fazer Login</h2>
<form method="POST" action="login.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required><br><br>

    <button type="submit">Entrar</button>
</form>

<?php

session_start(); // Inicia a sessão no topo!
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // 1. Busca do usuário pelo email
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // 2. Verificação da Senha Criptografada
        if (password_verify($senha, $usuario['senha'])) {

            // 3. Login bem-sucedido: Criação de variáveis de sessão
            $_SESSION['usuario_logado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            // 4. Redirecionamento para a página principal (ou área restrita)
            header("Location: dashboard.php");
            exit();

        } else {
            $mensagem = "Senha incorreta.";
        }
    } else {
        $mensagem = "Usuário não encontrado.";
    }
}
?>

<h2>Login</h2>
<p><?php echo $mensagem ?? ''; ?></p>
<form method="POST" action="login.php">
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit">Entrar</button>
</form>