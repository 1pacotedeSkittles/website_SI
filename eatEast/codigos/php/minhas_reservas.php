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
    $tempo_atual = time();

    // Loop para classificar as reservas
    foreach ($reservas as $reserva) {
        $data_reserva_ts = strtotime($reserva['data_hora_reserva']);

        if ($data_reserva_ts >= $tempo_atual) {
            $reservas_futuras[] = $reserva;
        } else {
            $reservas_passadas[] = $reserva;
        }
    }

} catch (PDOException $e) {
    // Em caso de erro na DB
    $erro_db = "Erro ao carregar reservas: " . $e->getMessage();
    $reservas = []; // Limpa a lista para o front-end
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minhas Reservas - eatEasy</title>
    <style>
        .reserva-futura { background-color: #e6ffe6; border-left: 5px solid green; padding: 10px; margin-bottom: 10px; }
        .reserva-passada { background-color: #f0f0f0; border-left: 5px solid gray; padding: 10px; margin-bottom: 10px; }
        .status-Pendente { color: orange; font-weight: bold; }
        .status-Confirmada { color: green; font-weight: bold; }
        .status-Cancelada { color: red; font-weight: bold; }
    </style>
</head>
<body>
<header>
    <h1>eatEasy - Minhas Reservas</h1>
    <div style="float: right;">
        <span>Cliente: **<?php echo htmlspecialchars($nome_cliente); ?>**</span>
        | <a href="logout.php">Sair</a>
    </div>
    <p><a href="pag_inicial_cliente.php">← Voltar à Lista de Restaurantes</a></p>
    <div style="clear: both;"></div>
</header>

<main>
    <?php if (isset($erro_db)): ?>
        <p style="color: red;"><?php echo $erro_db; ?></p>
    <?php endif; ?>

    <h2>Reservas Futuras (<?php echo count($reservas_futuras); ?>)</h2>
    <?php if (count($reservas_futuras) > 0): ?>
        <?php foreach ($reservas_futuras as $r): ?>
            <div class="reserva-futura">
                <strong>Reserva #<?php echo htmlspecialchars($r['id_reserva']); ?></strong>
                em **<?php echo htmlspecialchars($r['nome_restaurante']); ?>**
                para **<?php echo date('d/m/Y H:i', strtotime($r['data_hora_reserva'])); ?>**
                (<?php echo htmlspecialchars($r['num_pessoas']); ?> pessoas)
                <br>
                Status: <span class="status-<?php echo htmlspecialchars($r['status_reserva']); ?>"><?php echo htmlspecialchars($r['status_reserva']); ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Não tem reservas futuras agendadas.</p>
    <?php endif; ?>

    <hr>

    <h2>Reservas Passadas (<?php echo count($reservas_passadas); ?>)</h2>
    <?php if (count($reservas_passadas) > 0): ?>
        <?php foreach ($reservas_passadas as $r): ?>
            <div class="reserva-passada">
                Reserva #<?php echo htmlspecialchars($r['id_reserva']); ?>
                em **<?php echo htmlspecialchars($r['nome_restaurante']); ?>**
                em <?php echo date('d/m/Y H:i', strtotime($r['data_hora_reserva'])); ?>
                (<?php echo htmlspecialchars($r['num_pessoas']); ?> pessoas)
                <br>
                Status: <span class="status-<?php echo htmlspecialchars($r['status_reserva']); ?>"><?php echo htmlspecialchars($r['status_reserva']); ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Não tem reservas passadas registadas.</p>
    <?php endif; ?>

</main>
</body>
</html>