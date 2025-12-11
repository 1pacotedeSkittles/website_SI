<?php
echo "=== TESTE DE CONEXÃO ===<br><br>";

// Verifica o caminho
echo "Estou em: " . __DIR__ . "<br>";
echo "Vou procurar: " . __DIR__ . '/../../config/config.php<br><br>';

// Verifica se o ficheiro existe
if (file_exists(__DIR__ . '/../../config/config.php')) {
    echo "✅ Ficheiro config.php encontrado!<br>";
    require_once __DIR__ . '/../../config/config.php';
} else {
    die("❌ Ficheiro config.php NÃO encontrado!");
}

// Verifica se $conn existe
if (isset($conn)) {
    echo "✅ Variável \$conn existe!<br><br>";
} else {
    die("❌ Variável \$conn NÃO existe!");
}

// Testa a query
echo "=== CLIENTES ===<br>";
$query = "SELECT * FROM cliente";
$resultados = pg_query($conn, $query);

if (!$resultados) {
    die("❌ Erro na query: " . pg_last_error($conn));
}

$linhas = pg_fetch_all($resultados);

if ($linhas) {
    echo "✅ Encontrei " . count($linhas) . " cliente(s):<br><br>";
    foreach($linhas as $linha) {
        echo "Email: " . $linha['email'] . "<br>";
    }
} else {
    echo "⚠️ Nenhum cliente encontrado.";
}

pg_close($conn);
?>
