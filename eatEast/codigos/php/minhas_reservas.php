<?php
session_start();

// Proteção de Página
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'cliente') {
    $_SESSION['login_error'] = "Acesso restrito. Faça login primeiro.";
    header("Location: login.php");
    exit;
}

require '../DataBase/db-connect.php';

$id_cliente = $_SESSION['user_id'];
$nome_cliente = $_SESSION['user_nome'];
$tempo_atual = date('Y-m-d H:i:s');

// Consulta SQL para obter todas as reservas do cliente, juntando o nome do restaurante
$sql_reservas = "
    SELECT 
        r.id_reserva,
        r.data_hora_reserva,
        r.num_pessoas,
        r.status_reserva,
        res.nome AS nome_restaurante
    FROM reserva r
    JOIN restaurante res ON r.id_restaurante = res.id_restaurante
    WHERE r.id_cliente = :id_cliente
    ORDER BY r.data_hora_reserva DESC;
";

try {
    $stmt_reservas = $db->prepare($sql_reservas);
    $stmt_reservas->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt_reservas->execute();
    $reservas = $stmt_reservas->fetchAll(PDO::FETCH_ASSOC);

    // Variáveis para separar as reservas
    $reservas_futuras = [];
    $reservas_passadas = [];

    // Loop para classificar as reservas
    foreach ($reservas as $reserva) {
        $data_reserva_str = date('Y-m-d H:i:s', strtotime($reserva['data_hora_reserva']));

        if ($data_reserva_str >= $tempo_atual) {
            $reservas_futuras[] = $reserva;
        } else {
            $reservas_passadas[] = $reserva;
        }
    }

} catch (PDOException $e) {
    $erro_db = "Erro ao carregar reservas: " . $e->getMessage();
    $reservas = [];
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minhas Reservas - eatEasy</title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/minhas_reservas.css"> </head>
<body>

<header>
    <a href="pag_inicial_cliente.php" class="logo">eatEasy</a>
    <div class="nav-area">
        <div class="search-bar">
            <form action="pesquisar_restaurante.php" method="GET">
                <input type="search" name="query" placeholder="Pesquisar..." required>
                <button type="submit">🔍</button>
            </form>
        </div>

        <div class="nav-links user-profile">
            <span style="font-size: 20px;">👤</span>
            <span>Cliente: <strong><?php echo htmlspecialchars($nome_cliente); ?></strong></span>

            <a href="minhas_reservas.php">As minhas reservas</a>
            <a href="logout.php">Sair (Logout)</a>
        </div>
    </div>
</header>

<main>
    <?php if (isset($erro_db)): ?>
        <p style="color: red;"><?php echo $erro_db; ?></p>
    <?php endif; ?>

    <h2>As suas reservas</h2>

    <h3>Reservas Futuras (<?php echo count($reservas_futuras); ?>)</h3>
    <div class="reservas-listagem">
        <?php if (count($reservas_futuras) > 0): ?>
            <?php foreach ($reservas_futuras as $r): ?>
                <div class="reserva-item status-<?php echo htmlspecialchars($r['status_reserva']); ?>">
                    <div>
                        <strong><?php echo htmlspecialchars($r['nome_restaurante']); ?></strong>
                        <p>Data: <?php echo date('d/m/Y H:i', strtotime($r['data_hora_reserva'])); ?> | Pessoas: <?php echo htmlspecialchars($r['num_pessoas']); ?></p>
                    </div>
                    <div>
                        <span style="font-weight: bold;"><?php echo htmlspecialchars($r['status_reserva']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Não tem reservas futuras agendadas.</p>
        <?php endif; ?>
    </div>

    <hr>

    <h3>Reservas Passadas (<?php echo count($reservas_passadas); ?>)</h3>
    <div class="reservas-listagem">
        <?php if (count($reservas_passadas) > 0): ?>
            <?php foreach ($reservas_passadas as $r): ?>
                <div class="reserva-item reserva-historico">
                    <div>
                        <strong><?php echo htmlspecialchars($r['nome_restaurante']); ?></strong>
                        <p>Data: <?php echo date('d/m/Y H:i', strtotime($r['data_hora_reserva'])); ?> | Pessoas: <?php echo htmlspecialchars($r['num_pessoas']); ?></p>
                    </div>
                    <div>
                        <span style="color: gray;">Concluída</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Não tem reservas passadas registadas.</p>
        <?php endif; ?>
    </div>

</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2025 eatEasy. Todos os direitos reservados.</p>
    </div>
</footer>
</body>
</html>