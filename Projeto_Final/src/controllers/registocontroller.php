<?php
session_start();

if (file_exists(__DIR__ . '/../../config/config.php')) {
    require_once __DIR__ . '/../../config/config.php';
} else {
    die("❌ Ficheiro config.php NÃO encontrado!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Receber os dados do formulário
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $n_telemovel= trim($_POST['n_telemovel'] ?? '') ?: null;

    // 2. Validação dos campos obrigatórios
    $erros = [];

    if (empty($nome)) {
        $erros[] = "Nome é obrigatório.";
    }

    if (empty($email)) {
        $erros[] = "Email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Email inválido.";
    }
    if (empty($password)) {
        $erros[] = "Password é obrigatória.";
    }

    // 3. Hashing da Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Prepara a Query de Inserção (APENAS 3 COLUNAS)
    $query = "INSERT INTO cliente (username, email, password, n_telemovel) 
          VALUES ($1, $2, $3, $4)";

    $result = pg_query_params($conn, $query, array(
        $username,
        $email,
        $password,
        $n_telemovel
    ));

    if ($result) {
        $_SESSION['registo_sucesso'] = "Conta criada com sucesso!";
        header("Location: login.php");
    } else {
        $_SESSION['registo_erro'] = "Erro ao criar conta: " . pg_last_error($conn);
        header("Location: registo.php");
    }
}
pg_close($conn);
exit();
?>
