<h2>Crie sua Conta</h2>
<form method="POST" action="registro.php">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required><br><br>

    <button type="submit">Cadastrar</button>
</form>

<?php
require 'db.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // 1. Criptografia da Senha (Obrigatório!)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // 2. Preparação da Query para Inserção (Prevenção contra SQL Injection)
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        // 3. Execução da Query
        $stmt->execute([$nome, $email, $senha_hash]);
        $mensagem = "Cadastro realizado com sucesso! Faça o login.";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Código de erro para chave única duplicada (email)
            $mensagem = "Erro: Este e-mail já está cadastrado.";
        } else {
            $mensagem = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>

<h2>Cadastro</h2>
<p><?php echo $mensagem ?? ''; ?></p>
<form method="POST" action="registro.php">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit">Cadastrar</button>
</form>
