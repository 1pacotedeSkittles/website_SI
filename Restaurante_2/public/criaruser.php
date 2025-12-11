<?php
require_once '../config/config.php';

$username = 'joao';
$email = 'joao@gmail.com';
$password_plain = '123gay';
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

echo "Username: $username<br>";
echo "Email: $email<br>";
echo "Password (texto): $password_plain<br>";
echo "Password (hash): $password_hash<br><br>";

$query = "INSERT INTO cliente (username, email, password) VALUES ($1, $2, $3)";
$result = pg_query_params($conn, $query, array($username, $email, $password_hash));

if ($result) {
    echo "✅ <strong>Utilizador criado com sucesso!</strong><br>";
    echo "Podes fazer login com:<br>";
    echo "- Username: diogo<br>";
    echo "- Email: diogo@gmail.com<br>";
    echo "- Password: 123gay";
} else {
    echo "❌ Erro: " . pg_last_error($conn);
}

pg_close($conn);
?>
