<?php
session_start();

require '../DataBase/db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $password_inserida = $_POST['password'] ?? '';

    // Prepara a Query para buscar o utilizador pelo email (UNIQUE)
    $sql = "SELECT id_cliente, nome, password_hash FROM cliente WHERE email = :email";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o cliente existe E se a password está correta
        if ($cliente && password_verify($password_inserida, $cliente['password_hash'])) {


            // Cria variáveis de sessão
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $cliente['id_cliente'];
            $_SESSION['user_nome'] = $cliente['nome']; // Requisito: nome deve aparecer
            $_SESSION['user_type'] = 'cliente';

            // Redireciona para a página pós-login
            header("Location: pag_inicial_cliente.php");
            exit;

        } else {
            // Email não encontrado ou Password incorreta
            $_SESSION['login_error'] = "Email ou Password inválidos.";
            header("Location: login.php");
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Ocorreu um erro no servidor durante o login: " . $e->getMessage();
        header("Location: login.php");
        exit;
    }
} else {
    // Acesso direto, redireciona para o formulário
    header("Location: login.php");
    exit;
}
