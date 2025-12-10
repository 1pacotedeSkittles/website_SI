<?php
require_once('../../config/config.php');
$resultados = pg_query($conn, "select * from clientes") or die;
$resultados = pg_fetch_all($resultados);
foreach($resultados as $linha){ print $linha['email'] . "<br />";}