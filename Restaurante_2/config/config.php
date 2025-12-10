<?php
function getDBConnection()  /*criação de uma função para a conexão*/
{
    $str = "dbname=postgres user=postgres password=postgres host=localhost port=5432";
    $connection = pg_connect($str);

    if (!$connection) {
        die("Erro na ligacao");
    }

    // echo "Ligacao estabelecida!";
    // do something here
    return $connection;
}

// Cria a conexão global
$conn = getDBConnection();

