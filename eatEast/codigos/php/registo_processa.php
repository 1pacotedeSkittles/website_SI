<?php
session_start();
// O caminho foi ajustado para subir uma pasta e entrar em DataBase
require '../DataBase/db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitização e recolha dos dados
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    // $telemovel foi removido

    // 2. Validação dos campos obrigatórios
    if (empty($nome) || empty($email) || empty($password)) {
        $_SESSION['registo_mensagem'] = "Todos os campos obrigatórios devem ser preenchidos.";
        header("Location: registo.php");
        exit;
    }

    // 3. Hashing da Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Prepara a Query de Inserção (APENAS 3 COLUNAS)
    $sql = "INSERT INTO cliente (nome, email, password_hash) 
            VALUES (:nome, :email, :password_hash)";

    try {
        $stmt = $db->prepare($sql);

        // Liga os parâmetros à query (APENAS 3 PARAMETROS LIGADOS)
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $hashed_password);
        // O bind de :telemovel foi removido. Agora temos 3 binds para 3 placeholders.

        // 5. Executa a query
        $stmt->execute();

        // 6. Sucesso: Redireciona para o login com uma mensagem
        $_SESSION['registo_mensagem'] = "✅ Registo efetuado com sucesso! Pode agora fazer login.";
        header("Location: login.php");
        exit;

    } catch (PDOException $e) {
        // Captura o erro de email duplicado (23505)
        if ($e->getCode() == '23505') {
            $_SESSION['registo_mensagem'] = "❌ Erro: O email '$email' já está registado. Tente fazer login.";
        } else {
            // Outros erros
            $_SESSION['registo_mensagem'] = "❌ Erro ao registar: " . $e->getMessage();
        }
        header("Location: registo.php");
        exit;
    }
} else {
    // Acesso direto, redireciona para o formulário
    header("Location: registo.php");
    exit;
}
?>