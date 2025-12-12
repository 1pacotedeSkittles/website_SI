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
$id_restaurante = $_GET['id'] ?? null; // ID do restaurante vem do URL

if (empty($id_restaurante)) {
    // Se o ID do restaurante não for fornecido, redireciona para a lista
    header("Location: pag_inicial_cliente.php");
    exit;
}

$nome_cliente = $_SESSION['user_nome'];
$nome_restaurante = "Restaurante Desconhecido"; // Default

// 1. Buscar o nome do Restaurante
try {
    $sql_restaurante = "SELECT nome FROM restaurante WHERE id_restaurante = :id";
    $stmt = $db->prepare($sql_restaurante);
    $stmt->bindParam(':id', $id_restaurante);
    $stmt->execute();

    if ($restaurante = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nome_restaurante = $restaurante['nome'];
    } else {
        $_SESSION['error'] = "Restaurante não encontrado.";
        header("Location: pag_inicial_cliente.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erro ao carregar dados do restaurante: " . $e->getMessage();
    header("Location: pag_inicial_cliente.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Reservar em <?php echo htmlspecialchars($nome_restaurante); ?></title>
</head>
<body>
<header>
    <h1>eatEasy - Reservar Mesa</h1>
    <div style="float: right;">
        <span>Cliente: **<?php echo htmlspecialchars($nome_cliente); ?>**</span>
        | <a href="logout.php">Sair</a>
    </div>
    <div style="clear: both;"></div>
</header>

<main>
    <h2>Reservar em <?php echo htmlspecialchars($nome_restaurante); ?></h2>

    <?php
    // Exibir mensagens de erro ou sucesso após o processamento
    if (isset($_SESSION['reserva_mensagem'])) {
        $cor = str_contains($_SESSION['reserva_mensagem'], '✅') ? 'green' : 'red';
        echo '<p style="color: ' . $cor . ';">' . $_SESSION['reserva_mensagem'] . '</p>';
        unset($_SESSION['reserva_mensagem']);
    }
    ?>

    <form action="processa_reserva.php" method="POST">

        <input type="hidden" name="id_restaurante" value="<?php echo htmlspecialchars($id_restaurante); ?>">
        <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($id_cliente); ?>">

        <label for="data">Data da Reserva:</label>
        <input type="date" id="data" name="data" min="<?php echo date('Y-m-d'); ?>" required><br><br>

        <label for="hora">Hora da Reserva:</label>
        <input type="time" id="hora" name="hora" required><br><br>

        <label for="pessoas">Número de Pessoas:</label>
        <input type="number" id="pessoas" name="pessoas" min="1" required><br><br>

        <button type="submit">Confirmar Reserva</button>
    </form>

    <p><a href="pag_inicial_cliente.php">Voltar à lista de restaurantes</a></p>
</main>
</body>
</html>