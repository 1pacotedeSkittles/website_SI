<?php
require_once '../../config/config.php';  // Importa e já cria $conn automaticamente

$query = "SELECT * FROM cliente";
$resultados = pg_query($conn, $query) or die("Erro na query");

$linhas = pg_fetch_all($resultados);

if ($linhas) {
    foreach($linhas as $linha) {
        echo $linha['email'] . "<br />";
    }
} else {
    echo "Nenhum cliente encontrado.";
}

pg_close($conn);
?>