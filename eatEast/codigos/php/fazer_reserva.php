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
$id_restaurante = $_GET['id'] ?? null;

if (empty($id_restaurante)) {
    header("Location: pag_inicial_cliente.php");
    exit;
}

$nome_cliente = $_SESSION['user_nome'];
$nome_restaurante = "Restaurante Desconhecido";

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
    $_SESSION['error'] = "Erro ao carregar dados do restaurante.";
    header("Location: pag_inicial_cliente.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Reservar em <?php echo htmlspecialchars($nome_restaurante); ?></title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/login_registo.css">
    <link rel="stylesheet" href="../css/fazer_reserva.css">
</head>
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
    <div class="form-container">
        <div class="auth-form" style="max-width: 500px;">
            <h2>Reservar em <?php echo htmlspecialchars($nome_restaurante); ?></h2>

            <?php
            // Exibir mensagens de erro ou sucesso
            if (isset($_SESSION['reserva_mensagem'])) {
                $cor = str_contains($_SESSION['reserva_mensagem'], '✅') ? 'green' : 'red';
                echo '<p style="color: ' . $cor . ';">' . $_SESSION['reserva_mensagem'] . '</p>';
                unset($_SESSION['reserva_mensagem']);
            }
            ?>

            <form action="processa_reserva.php" method="POST" class="reserva-form">

                <input type="hidden" name="id_restaurante" value="<?php echo htmlspecialchars($id_restaurante); ?>">
                <input type="hidden" name="id_cliente" value="<?php echo htmlspecialchars($id_cliente); ?>">

                <label for="data" style="display: block; text-align: left; margin-bottom: 5px;">Data da Reserva:</label>
                <input type="date" id="data" name="data" min="<?php echo date('Y-m-d'); ?>" required><br>

                <label for="hora" style="display: block; text-align: left; margin-bottom: 5px;">Hora da Reserva:</label>
                <input type="time" id="hora" name="hora" required><br>

                <label for="pessoas" style="display: block; text-align: left; margin-bottom: 5px;">Número de Pessoas:</label>
                <input type="number" id="pessoas" name="pessoas" min="1" required><br>

                <button type="submit">Confirmar Reserva</button>
            </form>

            <p><a href="pag_inicial_cliente.php">Voltar à lista de restaurantes</a></p>
        </div>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2025 eatEasy. Todos os direitos reservados.</p>
    </div>
</footer>
</body>
</html>