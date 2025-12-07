<?php
// Configurações da Base de Dados PostgreSQL

// ⚠️ ATENÇÃO: Os valores $host, $user e $pass dependem da sua instalação do PostgreSQL
// No seu ambiente local, é provável que o $host seja 'localhost' e o $user seja 'postgres'.

$host = 'localhost';          // Endereço do servidor PostgreSQL (geralmente localhost)
$port = '5432';               // Porta padrão do PostgreSQL
$db   = 'postgres';            // Nome da base de dados, conforme sua imagem
$user = 'postgres';           // Seu utilizador do PostgreSQL (MUDE se for diferente)
$pass = 'postgres';  // ⚠️ !!! COLOQUE A PALAVRA-PASSE DO SEU UTILIZADOR POSTGRES !!!

// String de Conexão (DSN - Data Source Name)
$dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";

// Opções de Conexão PDO (Tratamento de Erros)
$options = [
    // Lança exceções em caso de erros de SQL
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // Define o fetch padrão para retornar arrays associativos
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Tenta estabelecer a conexão
    $pdo = new PDO($dsn);

} catch (\PDOException $e) {
    // Em caso de falha, interrompe o script e mostra a mensagem de erro
    die("Erro de conexão à Base de Dados PostgreSQL: " . $e->getMessage());
}

// Se a conexão for bem-sucedida, a variável $pdo estará disponível para ser usada
// em 'processa.login.php' e outros scripts.
?>