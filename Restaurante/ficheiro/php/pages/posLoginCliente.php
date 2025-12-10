<?php
session_start();
require '../includes/conexao.php';

// -------- OBTÉM DADOS DO CLIENTE ----------
$id_cliente = $_SESSION['id_cliente'];

$sql = $conn->prepare("SELECT nome FROM clientes WHERE id = ?");
$sql->bind_param("i", $id_cliente);
$sql->execute();
$result = $sql->get_result();
$cliente = $result->fetch_assoc();

// -------- OBTÉM RESERVAS ----------
$sql2 = $conn->prepare("SELECT nome_restaurante, data_reserva FROM reservas WHERE id_cliente = ?");
$sql2->bind_param("i", $id_cliente);
$sql2->execute();
$reservas = $sql2->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eatEasy - Cliente</title>
    <link rel="stylesheet" href="cliente_home.css">
</head>

<body>

<header>
    <?php require '../includes/navbar.php'; ?>
</header>

<main>
    <h2 class="titulo-secao">Faça já a sua reserva</h2>

    <section class="grid-restaurantes">

        <!-- Exemplo — substitui por query dinamizada depois -->
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <article class="card-restaurante">
                <a href="reserva_restaurante<?= $i ?>.php">
                    <div class="foto"></div>
                </a>
                <h3>Restaurante <?= $i ?></h3>
                <ul>
                    <li>Localização</li>
                    <li>Tipo comida</li>
                    <li>Preço médio por prato</li>
                </ul>
            </article>
        <?php endfor; ?>

    </section>
</main>

<!-- PAINEL LATERAL CLIENTE -->
<aside id="painel-cliente" class="painel-fechado">

    <div class="painel-header">
        <img src="../icons/user_big.png" class="icon-user">
        <h3><?= $cliente['nome'] ?></h3>
    </div>

    <h4 class="titulo-reservas">📘 As minhas reservas</h4>

    <ul class="lista-reservas">

        <?php if ($reservas->num_rows === 0): ?>
            <li>Não tem reservas ainda.</li>
        <?php else: ?>
            <?php while($r = $reservas->fetch_assoc()): ?>
                <li>
                    <?= $r['nome_restaurante'] ?>
                    <span><?= $r['data_reserva'] ?></span>
                </li>
            <?php endwhile; ?>
        <?php endif; ?>

    </ul>

    <p class="copyright">© 2025 eatEasy<br>Todos os direitos reservados</p>
</aside>

<script src="cliente_home.js"></script>

</body>
</html>
