<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

require '../DataBase/db-connect.php';

$id_restaurante = $_GET['id'] ?? null;
$id_admin_logado = $_SESSION['user_id'];

if (empty($id_restaurante)) {
    $_SESSION['msg_admin'] = "❌ Erro: ID do restaurante não fornecido.";
    header("Location: pag_inicial_admin.php");
    exit;
}

// Carregar dados atuais do restaurante
try {
    $sql_data = "SELECT nome, local_morada, preco_medio, id_admin_proprietario FROM restaurante WHERE id_restaurante = :id";
    $stmt_data = $db->prepare($sql_data);
    $stmt_data->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
    $stmt_data->execute();
    $restaurante = $stmt_data->fetch(PDO::FETCH_ASSOC);

    if (!$restaurante || $restaurante['id_admin_proprietario'] != $id_admin_logado) {
        $_SESSION['msg_admin'] = "❌ Erro: Restaurante não encontrado ou não tem permissão para editar.";
        header("Location: pag_inicial_admin.php");
        exit;
    }

    // Carregar todos os Tipos de Cozinha disponíveis
    $stmt_cozinhas = $db->query("SELECT id_tipo_cozinha, designacao FROM tipo_cozinha ORDER BY designacao");
    $tipos_cozinha_disponiveis = $stmt_cozinhas->fetchAll(PDO::FETCH_ASSOC);

    // Carregar os Tipos de Cozinha ATUAIS deste restaurante
    $sql_current_types = "SELECT id_tipo_cozinha FROM restaurante_tipo_cozinha WHERE id_restaurante = :id";
    $stmt_current_types = $db->prepare($sql_current_types);
    $stmt_current_types->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
    $stmt_current_types->execute();
    // Converte o resultado para um array simples de IDs para verificação fácil
    $tipos_cozinha_atuais = array_column($stmt_current_types->fetchAll(PDO::FETCH_ASSOC), 'id_tipo_cozinha');

} catch (PDOException $e) {
    $_SESSION['msg_admin'] = "❌ Erro DB ao carregar dados: " . $e->getMessage();
    header("Location: pag_inicial_admin.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Atualizar <?php echo htmlspecialchars($restaurante['nome']); ?></title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/login_registo.css"> </head>
<body>

<header>
    <a href="../php/pag_inicial_cliente.php" class="logo">eatEasy Admin</a>
    <div class="nav-area">
        <div class="nav-links user-profile">
            <span>Admin: <strong><?php echo htmlspecialchars($_SESSION['user_nome']); ?></strong></span>
            <a href="logout_admin.php">Sair (Logout)</a>
        </div>
    </div>
</header>

<main>
    <div class="form-container">
        <div class="auth-form" style="max-width: 500px; text-align: left;">
            <h2>Atualizar: <?php echo htmlspecialchars($restaurante['nome']); ?></h2>
            <p><a href="pag_inicial_admin.php">← Voltar ao Painel</a></p>

            <?php
            if (isset($_SESSION['msg_admin'])): ?>
                <p style="color: <?php echo str_contains($_SESSION['msg_admin'], 'sucesso') ? 'green' : 'red'; ?>; font-weight: bold;">
                    <?php echo $_SESSION['msg_admin']; unset($_SESSION['msg_admin']); ?>
                </p>
            <?php endif; ?>

            <form action="processa_atualizar_restaurante.php" method="POST" class="reserva-form">

                <input type="hidden" name="id_restaurante" value="<?php echo htmlspecialchars($id_restaurante); ?>">

                <label for="nome">Nome do Restaurante:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($restaurante['nome']); ?>" required><br>

                <label for="local_morada">Local/Morada:</label>
                <input type="text" id="local_morada" name="local_morada" value="<?php echo htmlspecialchars($restaurante['local_morada']); ?>" required><br>

                <label for="preco_medio">Preço Médio de uma Refeição (€):</label>
                <input type="number" id="preco_medio" name="preco_medio" step="0.01" min="0" value="<?php echo htmlspecialchars($restaurante['preco_medio']); ?>" required><br>

                <label>Tipo(s) de Cozinha:</label>
                <div class="checkbox-group">
                    <?php foreach ($tipos_cozinha_disponiveis as $cozinha):
                        // Verifica se o ID da cozinha está na lista de tipos atuais para marcar
                        $checked = in_array($cozinha['id_tipo_cozinha'], $tipos_cozinha_atuais) ? 'checked' : '';
                        ?>
                        <input type="checkbox" name="tipos_cozinha[]" id="cozinha_<?php echo $cozinha['id_tipo_cozinha']; ?>" value="<?php echo $cozinha['id_tipo_cozinha']; ?>" <?php echo $checked; ?>>
                        <label for="cozinha_<?php echo $cozinha['id_tipo_cozinha']; ?>" style="display: inline; font-weight: normal; margin-right: 15px;">
                            <?php echo htmlspecialchars($cozinha['designacao']); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div><br>

                <button type="submit">Atualizar Restaurante</button>
            </form>
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