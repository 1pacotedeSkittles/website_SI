<?php
// Verifica se o ficheiro existe
if (file_exists(__DIR__ . '/../../config/config.php')) {
    require_once __DIR__ . '/../../config/config.php';
} else {
    die("❌ Ficheiro config.php NÃO encontrado!");
}

$username_ou_email = $_POST['username_ou_email'];
$password = $_POST['password'];

// Buscar na tabela cliente (não users!)
$query = "SELECT * FROM cliente WHERE username = $1 OR email = $1";
$result = pg_query_params($conn, $query, array($username_ou_email));

if (!$result) {
    die("❌ Erro na query: " . pg_last_error($conn));
}

$user = pg_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    // Login com sucesso
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    pg_close($conn);
    header('Location: ../../public/index.php');
    exit();

} else {
    // Login falhou
    pg_close($conn);
    header('Location: ../../public/login.php?erro=1');
    exit();
}
?>
