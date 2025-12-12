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

// 1. Carregar dados atuais do restaurante
$sql_data = "SELECT nome, local_morada, preco_medio, id_admin_proprietario FROM restaurante WHERE id_restaurante = :id";
$stmt_data = $db->prepare($sql_data);
$stmt_data->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
$stmt_data->execute();
$restaurante = $stmt_data->fetch(PDO::FETCH_ASSOC);

if (!$restaurante || $restaurante['id_admin_proprietario'] != $id_admin_logado) {
    $_SESSION['msg_admin'] = "❌ Erro: Restaurante não encontrado ou não tem permissão.";
    header("Location: pag_inicial_admin.php");
    exit;
}

// 2. Carregar todos os Tipos de Cozinha disponíveis
$stmt_cozinhas = $db->query("SELECT id_tipo_cozinha, designacao FROM tipo_cozinha ORDER BY designacao");
$tipos_cozinha_disponiveis = $stmt_cozinhas->fetchAll(PDO::FETCH_ASSOC);

// 3. Carregar os Tipos de Cozinha ATUAIS deste restaurante
$sql_current_types = "SELECT id_tipo_cozinha FROM restaurante_tipo_cozinha WHERE id_restaurante = :id";
$stmt_current_types = $db->prepare($sql_current_types);
$stmt_current_types->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
$stmt_current_types->execute();
// Converte o resultado para um array de IDs para verificação
$tipos_cozinha_atuais = array_column($stmt_current_types->fetchAll(PDO::FETCH_ASSOC), 'id_tipo_cozinha');

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Atualizar <?php echo htmlspecialchars($restaurante['nome']); ?></title>
</head>
<body>
<header>
    <h1>Atualizar: <?php echo htmlspecialchars($restaurante['nome']); ?></h1>
    <p><a href="pag_inicial_admin.php">← Voltar ao Painel</a></p>
</header>

<main>
    <?php
    if (isset($_SESSION['msg_admin'])): ?>
        <p style="color: <?php echo str_contains($_SESSION['msg_admin'], 'sucesso') ? 'green' : 'red'; ?>;">
            <?php echo $_SESSION['msg_admin']; unset($_SESSION['msg_admin']); ?>
        </p>
    <?php endif; ?>

    <form action="processa_atualizar_restaurante.php" method="POST">

        <input type="hidden" name="id_restaurante" value="<?php echo htmlspecialchars($id_restaurante); ?>">

        <label for="nome">Nome do Restaurante:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($restaurante['nome']); ?>" required><br><br>

        <label for="local_morada">Local/Morada:</label>
        <input type="text" id="local_morada" name="local_morada" value="<?php echo htmlspecialchars($restaurante['local_morada']); ?>" required><br><br>

        <label for="preco_medio">Preço Médio de uma Refeição (€):</label>
        <input type="number" id="preco_medio" name="preco_medio" step="0.01" min="0" value="<?php echo htmlspecialchars($restaurante['preco_medio']); ?>" required><br><br>

        <label>Tipo(s) de Cozinha:</label><br>
        <div style="border: 1px solid #ddd; padding: 10px;">
            <?php foreach ($tipos_cozinha_disponiveis as $cozinha):
                // Marca a caixa se o ID da cozinha estiver na lista de tipos atuais
                $checked = in_array($cozinha['id_tipo_cozinha'], $tipos_cozinha_atuais) ? 'checked' : '';
                ?>
                <input type="checkbox" name="tipos_cozinha[]" value="<?php echo $cozinha['id_tipo_cozinha']; ?>" <?php echo $checked; ?>>
                <?php echo htmlspecialchars($cozinha['designacao']); ?><br>
            <?php endforeach; ?>
        </div><br>

        <button type="submit">Atualizar Restaurante</button>
    </form>
</main>
</body>
</html>