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
$msg_erro = null;


// PROCESSAR STATUS (Confirmar/Cancelar) - POST

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id_reserva'])) {
    $id_reserva = filter_input(INPUT_POST, 'id_reserva', FILTER_VALIDATE_INT);
    $nova_status = ($_POST['action'] === 'confirmar') ? 'Confirmada' : 'Cancelada';
    $msg_sucesso = ($nova_status === 'Confirmada') ? 'confirmada' : 'cancelada';

    if ($id_reserva) {
        try {
            // Verifica se este Admin é proprietário do restaurante associado à reserva
            $sql_check = "
                SELECT 1 
                FROM reserva r
                JOIN restaurante res ON r.id_restaurante = res.id_restaurante
                WHERE r.id_reserva = :id_reserva AND res.id_admin_proprietario = :id_admin
            ";
            $stmt_check = $db->prepare($sql_check);
            $stmt_check->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
            $stmt_check->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                // Se a reserva for válida, atualiza o status
                $sql_update = "UPDATE reserva SET status_reserva = :status WHERE id_reserva = :id_reserva";
                $stmt_update = $db->prepare($sql_update);
                $stmt_update->bindParam(':status', $nova_status);
                $stmt_update->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
                $stmt_update->execute();

                $_SESSION['msg_admin'] = "✅ Reserva #{$id_reserva} foi $msg_sucesso com sucesso.";
            } else {
                $_SESSION['msg_admin'] = "❌ Erro: Reserva inválida ou não pertence a este administrador.";
            }
            // Redireciona para evitar reenvio do formulário e recarrega a página
            header("Location: listar_reservas.php");
            exit;

        } catch (PDOException $e) {
            $msg_erro = "Erro ao processar a ação: " . $e->getMessage();
        }
    }
}
// ==========================================================

// Consulta: Lista todas as Reservas para os restaurantes deste Admin
$reservas = [];
try {
    $sql_reservas = "
        SELECT 
            r.id_reserva, 
            r.data_hora_reserva, 
            r.num_pessoas, 
            r.status_reserva,
            res.nome AS nome_restaurante,
            c.nome AS nome_cliente,
            c.email AS email_cliente
        FROM reserva r
        JOIN restaurante res ON r.id_restaurante = res.id_restaurante
        JOIN cliente c ON r.id_cliente = c.id_cliente
        WHERE res.id_admin_proprietario = :id_admin
        ORDER BY r.data_hora_reserva DESC;
    ";

    $stmt_reservas = $db->prepare($sql_reservas);
    $stmt_reservas->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
    $stmt_reservas->execute();
    $reservas = $stmt_reservas->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $msg_erro = "Erro ao listar reservas: Falha na comunicação com a base de dados.";
}

// Função para formatar a data/hora
function formatarData($data_hora) {
    $data_obj = new DateTime($data_hora);
    return $data_obj->format('d/m/Y H:i');
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Reservas - Admin</title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/minhas_reservas.css"> </head>
<body>

<header>
    <a href="pag_inicial_admin.php" class="logo">eatEasy Admin</a>
    <div class="nav-area">
        <div class="nav-links user-profile">
            <span>Admin: <strong><?php echo htmlspecialchars($nome_admin); ?></strong></span>
            <a href="logout_admin.php">Sair (Logout)</a>
        </div>
    </div>
</header>

<main>
    <h2>Gestão de Reservas</h2>
    <p><a href="pag_inicial_admin.php">← Voltar ao Painel</a></p>

    <?php
    // Exibir mensagens de sucesso ou erro (após a gestão)
    if (isset($_SESSION['msg_admin'])):
        $cor = str_contains($_SESSION['msg_admin'], '✅') ? 'green' : 'red';
        ?>
        <p style="color: <?php echo $cor; ?>; font-weight: bold;">
            <?php echo $_SESSION['msg_admin']; unset($_SESSION['msg_admin']); ?>
        </p>
    <?php endif; ?>

    <?php if ($msg_erro): ?>
        <p style="color: red;"><?php echo $msg_erro; ?></p>
    <?php endif; ?>

    <h3>Reservas Recebidas para os Seus Restaurantes</h3>

    <div class="reservas-listagem">
        <?php if (count($reservas) > 0): ?>
            <ul>
                <?php
                $data_atual = new DateTime();
                foreach ($reservas as $reserva):
                    $data_reserva = new DateTime($reserva['data_hora_reserva']);
                    $is_passada = $data_reserva < $data_atual;
                    $status_class = 'status-' . str_replace(' ', '', $reserva['status_reserva']);

                    // Adiciona classe 'reserva-historico' se for passada
                    $item_class = 'reserva-item ' . $status_class . ($is_passada ? ' reserva-historico' : '');
                    ?>
                    <li class="<?php echo $item_class; ?>">
                        <div class="reserva-details">
                            <p><strong>Reserva #<?php echo htmlspecialchars($reserva['id_reserva']); ?></strong> (<?php echo htmlspecialchars($reserva['nome_restaurante']); ?>)</p>
                            <p>Data/Hora: <strong><?php echo formatarData($reserva['data_hora_reserva']); ?></strong></p>
                            <p>Cliente: <?php echo htmlspecialchars($reserva['nome_cliente']); ?> (<?php echo htmlspecialchars($reserva['email_cliente']); ?>)</p>
                            <p>Pessoas: <?php echo htmlspecialchars($reserva['num_pessoas']); ?></p>
                            <p>Status: <span style="font-weight: bold;"><?php echo htmlspecialchars($reserva['status_reserva']); ?></span></p>
                        </div>

                        <div class="reserva-actions">
                            <?php
                            // O Admin só pode confirmar ou cancelar reservas Pendentes E Futuras
                            if ($reserva['status_reserva'] === 'Pendente' && !$is_passada): ?>
                                <form action="lista_reservas.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                    <button type="submit" name="action" value="confirmar" class="btn-action btn-confirmar">
                                        Confirmar
                                    </button>
                                </form>
                                <form action="lista_reservas.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">
                                    <button type="submit" name="action" value="cancelar" class="btn-action btn-cancelar" onclick="return confirm('Confirma o cancelamento da Reserva #<?php echo $reserva['id_reserva']; ?>?')">
                                        Cancelar
                                    </button>
                                </form>
                            <?php elseif ($is_passada): ?>
                                <span style="color: #6c757d; font-style: italic;">(Histórico - Ação Indisponível)</span>
                            <?php else: ?>
                                <span style="color: #007bff; font-weight: bold;">(Status Finalizado)</span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhuma reserva encontrada para os seus restaurantes.</p>
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