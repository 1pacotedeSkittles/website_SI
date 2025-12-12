<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

require '../DataBase/db-connect.php';

$id_admin = $_SESSION['user_id'];
$nome_admin = $_SESSION['user_nome'];
$tempo_atual = date('Y-m-d H:i:s');

// SQL para buscar todas as reservas para os restaurantes que este Admin possui
$sql_reservas = "
    SELECT 
        r.id_reserva,
        r.data_hora_reserva,
        r.num_pessoas,
        r.status_reserva,
        res.nome AS nome_restaurante,
        c.nome AS nome_cliente
    
    FROM reserva r
    JOIN restaurante res ON r.id_restaurante = res.id_restaurante
    JOIN cliente c ON r.id_cliente = c.id_cliente
    WHERE res.id_admin_proprietario = :id_admin
    ORDER BY r.data_hora_reserva DESC;
";

try {
    $stmt = $db->prepare($sql_reservas);
    $stmt->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
    $stmt->execute();
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Classificação de Reservas
    $pendentes = [];
    $confirmadas = [];
    $historico = [];

    foreach ($reservas as $r) {
        $reserva_data = date('Y-m-d H:i:s', strtotime($r['data_hora_reserva']));

        if ($reserva_data < $tempo_atual) {
            $historico[] = $r;
        } elseif ($r['status_reserva'] == 'Pendente') {
            $pendentes[] = $r;
        } else {
            $confirmadas[] = $r;
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
    <title>Reservas - Admin</title>
    <style>
        .reserva-pendente { background-color: #ffffe0; border: 1px solid orange; padding: 10px; margin-bottom: 10px; }
        .reserva-confirmada { background-color: #e6ffe6; border: 1px solid green; padding: 10px; margin-bottom: 10px; }
        .reserva-historico { background-color: #f0f0f0; border: 1px solid gray; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
<header>
    <h1>Gestão de Reservas</h1>
    <p>Admin: **<?php echo htmlspecialchars($nome_admin); ?>** | <a href="pag_inicial_admin.php">← Voltar ao Painel</a></p>
</header>

<main>
    <?php if (isset($erro_db)): ?>
        <p style="color: red;"><?php echo $erro_db; ?></p>
    <?php endif; ?>

    <h2>Reservas Pendentes (<?php echo count($pendentes); ?>)</h2>
    <?php if (count($pendentes) > 0): ?>
        <?php foreach ($pendentes as $r): ?>
            <div class="reserva-pendente">
                <p><strong>Restaurante:</strong> <?php echo htmlspecialchars($r['nome_restaurante']); ?></p>
                <p><strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i', strtotime($r['data_hora_reserva'])); ?></p>
                <p><strong>Cliente:</strong> <?php echo htmlspecialchars($r['nome_cliente']); ?></p>
                <p><strong>Pessoas:</strong> <?php echo htmlspecialchars($r['num_pessoas']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Não há reservas pendentes.</p>
    <?php endif; ?>

</main>
</body>
</html>
