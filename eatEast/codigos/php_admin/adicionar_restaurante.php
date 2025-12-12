<?php
session_start();
// Proteção de Página
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

// O caminho foi ajustado para subir dois níveis
require '../DataBase/db-connect.php';

$id_admin = $_SESSION['user_id'];
$nome_admin = $_SESSION['user_nome'];
$erro_cozinha = null;

// 1. Buscar todos os Tipos de Cozinha disponíveis para listar no formulário
$tipos_cozinha = [];
try {
    $stmt = $db->query("SELECT id_tipo_cozinha, designacao FROM tipo_cozinha ORDER BY designacao");
    $tipos_cozinha = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erro_cozinha = "Erro ao carregar tipos de cozinha: " . $e->getMessage();
}

?>
    <!DOCTYPE html>
    <html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Restaurante - Admin</title>
</head>
<body>
<header>
    <h1>Adicionar Novo Restaurante</h1>
    <p>Admin: **<?php echo htmlspecialchars($nome_admin); ?>** | <a href="pag_inicial_admin.php">← Voltar ao Painel</a></p>
</header>

<main>
    <?php
    // Exibir mensagens de sucesso ou erro (vindas do processamento)
    if (isset($_SESSION['msg_admin'])): ?>
        <p style="color: <?php echo str_contains($_SESSION['msg_admin'], 'sucesso') ? 'green' : 'red'; ?>;">
            <?php echo $_SESSION['msg_admin']; unset($_SESSION['msg_admin']); ?>
        </p>
    <?php endif; ?>

    <form action="processa_adicionar_restaurante.php" method="POST">
        <input type="hidden" name="id_admin" value="<?php echo htmlspecialchars($id_admin); ?>">

        <label for="nome">Nome do Restaurante:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="local_morada">Local/Morada:</label>
        <input type="text" id="local_morada" name="local_morada" required><br><br>

        <label for="preco_medio">Preço Médio de uma Refeição (€):</label>
        <input type="number" id="preco_medio" name="preco_medio" step="0.01" min="0" required><br><br>

        <label>Tipo(s) de Cozinha:</label><br>
        <?php if (isset($erro_cozinha)): ?>
            <p style="color: red;"><?php echo $erro_cozinha; ?></p>
        <?php elseif (empty($tipos_cozinha)): ?>
            <p>Nenhum tipo de cozinha disponível. (Adicione na tabela `tipo_cozinha` se necessário).</p>
        <?php else: ?>
            <div style="border: 1px solid #ddd; padding: 10px;">
                <?php foreach ($tipos_cozinha as $cozinha): ?>
                    <input type="checkbox" name="tipos_cozinha[]" value="<?php echo $cozinha['id_tipo_cozinha']; ?>">
                    <?php echo htmlspecialchars($cozinha['designacao']); ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?><br>

        <button type="submit">Registar Restaurante</button>
    </form>
</main>
</body>
    </html><?php
