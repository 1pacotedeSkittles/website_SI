<?php
$host = 'localhost'; // Seu host (geralmente localhost)
$db   = 'seu_banco_de_dados'; // Nome do seu banco de dados
$user = 'seu_usuario'; // Usuário do MySQL (geralmente root)
$pass = 'sua_senha'; // Senha do MySQL

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>


