<?php
session_start();
// Proteção de Página
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

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
    $erro_cozinha = "Erro ao carregar tipos de cozinha.";
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Restaurante - Admin</title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/login_registo.css"> </head>
<body>

<header>
    <a href="../php/pag_inicial_cliente.php" class="logo">eatEasy Admin</a>
    <div class="nav-area">
        <div class="nav-links user-profile">
            <span>Admin: <strong><?php echo htmlspecialchars($nome_admin); ?></strong></span>
            <a href="logout_admin.php">Sair (Logout)</a>
        </div>
    </div>
</header>

<main>
    <div class="form-container">
        <div class="auth-form" style="max-width: 500px; text-align: left;">
            <h2>Adicionar Novo Restaurante</h2>
            <p><a href="pag_inicial_admin.php">← Voltar ao Painel</a></p>

            <?php
            // Exibir mensagens de sucesso ou erro (vindas do processamento)
            if (isset($_SESSION['msg_admin'])): ?>
                <p style="color: <?php echo str_contains($_SESSION['msg_admin'], 'sucesso') ? 'green' : 'red'; ?>; font-weight: bold;">
                    <?php echo $_SESSION['msg_admin']; unset($_SESSION['msg_admin']); ?>
                </p>
            <?php endif; ?>

            <form action="processa_adicionar_restaurante.php" method="POST" class="reserva-form">
                <input type="hidden" name="id_admin" value="<?php echo htmlspecialchars($id_admin); ?>">

                <label for="nome">Nome do Restaurante:</label>
                <input type="text" id="nome" name="nome" required><br>

                <label for="local_morada">Local/Morada:</label>
                <input type="text" id="local_morada" name="local_morada" required><br>

                <label for="preco_medio">Preço Médio de uma Refeição (€):</label>
                <input type="number" id="preco_medio" name="preco_medio" step="0.01" min="0" required><br>

                <label>Tipo(s) de Cozinha:</label>
                <?php if (isset($erro_cozinha)): ?>
                    <p style="color: red;"><?php echo $erro_cozinha; ?></p>
                <?php elseif (empty($tipos_cozinha)): ?>
                    <p style="margin-top: 5px;">Nenhum tipo de cozinha disponível. Adicione tipos na base de dados primeiro.</p>
                <?php else: ?>
                    <div class="checkbox-group">
                        <?php foreach ($tipos_cozinha as $cozinha): ?>
                            <input type="checkbox" name="tipos_cozinha[]" id="cozinha_<?php echo $cozinha['id_tipo_cozinha']; ?>" value="<?php echo $cozinha['id_tipo_cozinha']; ?>">
                            <label for="cozinha_<?php echo $cozinha['id_tipo_cozinha']; ?>" style="display: inline; font-weight: normal; margin-right: 15px;">
                                <?php echo htmlspecialchars($cozinha['designacao']); ?>
                            </label><br>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?><br>

                <button type="submit">Registar Restaurante</button>
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