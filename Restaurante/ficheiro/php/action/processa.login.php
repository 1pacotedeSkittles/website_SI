<?php
// 1. Iniciar a Sessão e Incluir a Conexão
session_start();
// O caminho é ../includes/ porque estamos dentro de action/
require '../includes/database.connect.php';

// Verifica se o método de requisição é POST (submissão do formulário)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Obter e Sanitizar os Dados
    // O campo 'nome_ou_email' é o que o cliente usará para login.
    $identificador = trim($_POST['nome_ou_email']);
    $password_input = $_POST['password'];

    // 3. Preparar a Query (Procura pelo cliente usando o email/identificador)
    // Usamos 'prepared statements' para prevenir ataques de SQL Injection.
    $sql = "SELECT id_cliente, email, password FROM cliente WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$identificador]);
    $cliente = $stmt->fetch(); // Obtém o registo do cliente

    // 4. Verificar Cliente e Palavra-Passe
    if ($cliente) {
        // Cliente encontrado, verificar a hash da palavra-passe
        // A função password_verify é CRUCIAL para segurança.
        if (password_verify($password_input, $cliente['password'])) {

            // Sucesso no Login!
            // Armazena as informações na sessão
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id_cliente'] = $cliente['id_cliente'];
            $_SESSION['email'] = $cliente['email'];

            // Redireciona para a página pós-login
            header("location: ../pages/posLoginCliente.php");
            exit;

        } else {
            // Palavra-passe incorreta
            $login_erro = "Palavra-passe inválida.";
        }
    } else {
        // Utilizador (email) não encontrado
        $login_erro = "Utilizador não encontrado.";
    }

    // Se houver erro, armazena a mensagem de erro na sessão e redireciona de volta para a página de login
    $_SESSION['login_erro'] = $login_erro;
    header("location: ../pages/login.php");
    exit;
} else {
    // Acesso direto ao ficheiro sem submissão de formulário
    header("location: ../pages/login.php");
    exit;
}
?>