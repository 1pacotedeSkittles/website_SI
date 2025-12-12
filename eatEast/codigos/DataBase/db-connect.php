<?php
// Configurações da Base de Dados PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "postgres"; // A base de dados principal, conforme a sua imagem
$user = "postgres";
$password = "postgres"; // Por favor, use a sua password real. Se a sua DB estiver segura, altere este valor.

$connection_string = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

try {
    // 1. Estabelece a conexão PDO
    $db = new PDO($connection_string);

    // 2. Define o modo de erro para exceções
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: define o charset para UTF-8
    $db->exec("SET NAMES 'utf8'");

} catch (PDOException $e) {
    // 3. Em caso de erro, termina o script
    echo "🚨 Erro de Conexão com a Base de Dados: " . $e->getMessage();
    // Em produção, esta mensagem não deve ser mostrada.
    die();
}