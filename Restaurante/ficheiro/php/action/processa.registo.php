<?php
session_start();
// Caminho de volta (..) para a pasta 'php/', depois para 'includes/'
require '../includes/database.connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Sanitizar e Obter Dados
    // Os nomes dos inputs devem corresponder aos nomes no formulário HTML (loginRegisto.php)
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $n_telemovel = trim($_POST['n_telemovel']);
    $password_simples = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // 2. Validação Básica
    if (empty($nome) || empty($email) || empty($password_simples) || empty($password_confirm)) {
        $_SESSION['registo_erro'] = "Todos os campos obrigatórios devem ser preenchidos.";
        header("location: ../pages/loginRegisto.php");
        exit;
    }

    if ($password_simples !== $password_confirm) {
        $_SESSION['registo_erro'] = "As palavras-passe não coincidem.";
        header("location: ../pages/loginRegisto.php");
        exit;
    }

    // 3. Verificar se o Email Já Existe (Prevenção de Duplicados)
    $sql_check = "SELECT id_cliente FROM cliente WHERE email = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$email]);

    if ($stmt_check->rowCount() > 0) {
        $_SESSION['registo_erro'] = "Este email já se encontra registado.";
        header("location: ../pages/loginRegisto.php");
        exit;
    }

    // 4. HASH da Palavra-Passe (CRUCIAL para segurança)
    $password_hash = password_hash($password_simples, PASSWORD_DEFAULT);

    // 5. Inserir Novo Cliente (Usando Prepared Statement para PostgreSQL)
    try {
        $sql_insert = "INSERT INTO cliente (nome, email, password, n_telemovel) 
                       VALUES (?, ?, ?, ?)";

        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->execute([$nome, $email, $password_hash, $n_telemovel]);

        // Sucesso: Redireciona para a página de login
        $_SESSION['login_sucesso'] = "Registo efetuado com sucesso! Faça login.";
        header("location: ../pages/login.php");
        exit;

    } catch (PDOException $e) {
        // Erro genérico da BD
        $_SESSION['registo_erro'] = "Erro ao registar: Tente novamente mais tarde. (" . $e->getMessage() . ")";
        header("location: ../pages/loginRegisto.php");
        exit;
    }
} else {
    // Acesso direto ao ficheiro sem formulário
    header("location: ../pages/loginRegisto.php");
    exit;
}
?>