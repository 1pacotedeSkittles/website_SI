<?php
session_start();

// 1. Proteção de Página
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['admin_login_error'] = "Acesso negado. Faça login como Administrador.";
    header("Location: login_admin.php");
    exit;
}

// O caminho foi ajustado para subir dois níveis
require '../DataBase/db-connect.php';

$id_admin = $_SESSION['user_id'];
$nome_admin = $_SESSION['user_nome'];

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração - eatEasy</title>
    <style>
        /* Estilos básicos para o painel */
        body { font-family: Arial, sans-serif; margin: 20px; }
        header { border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        .header-info { float: right; }
        .restaurante-item { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; list-style-type: none; }
        .restaurante-actions a { margin-right: 15px; }
    </style>
</head>
<body>
<header>
    <h1>Painel de Administração</h1>
    <div class="header-info">
        <span>Admin: **<?php echo htmlspecialchars($nome_admin); ?>**</span>
        | <a href="logout_admin.php">Sair (Logout)</a>
    </div>
    <div style="clear: both;"></div>
</header>

<main>
    <h2>Ações de Gestão</h2>

    <p>
        <a href="adicionar_restaurante.php">Adicionar Novo Restaurante</a> |
        <a href="lista_reservas.php">Ver Reservas para Meus Restaurantes</a>
    </p>

    <hr>

    <h3>Meus Restaurantes</h3>

    <?php
    // 1. Verificar se existem mensagens de sucesso ou erro (ex: após Adicionar/Remover)
    if (isset($_SESSION['msg_admin'])) {
        $cor = str_contains($_SESSION['msg_admin'], 'sucesso') ? 'green' : 'red';
        echo '<p style="color: ' . $cor . ';">' . $_SESSION['msg_admin'] . '</p>';
        unset($_SESSION['msg_admin']);
    }

    // 2. Consulta: Listar Restaurantes geridos por este Admin
    $sql_restaurantes = "
            SELECT 
                r.id_restaurante, 
                r.nome, 
                r.local_morada, 
                r.preco_medio
            FROM restaurante r
            WHERE r.id_admin_proprietario = :id_admin
            ORDER BY r.nome;
        ";

    try {
        $stmt_restaurantes = $db->prepare($sql_restaurantes);
        $stmt_restaurantes->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
        $stmt_restaurantes->execute();
        $restaurantes = $stmt_restaurantes->fetchAll(PDO::FETCH_ASSOC);

        if (count($restaurantes) > 0) {
            echo '<ul>';
            foreach ($restaurantes as $restaurante) {
                echo '<li class="restaurante-item">';
                echo '  <strong>' . htmlspecialchars($restaurante['nome']) . '</strong><br>';
                echo '  Local: ' . htmlspecialchars($restaurante['local_morada']) . ' | ';
                echo '  Preço Médio: ' . htmlspecialchars($restaurante['preco_medio']) . '€<br>';

                // Botões de Ação
                echo '  <div class="restaurante-actions">';
                echo '      <a href="atualizar_restaurante.php?id=' . $restaurante['id_restaurante'] . '">Atualizar</a>';
                echo '      <a href="remover_restaurante.php?id=' . $restaurante['id_restaurante'] . '" onclick="return confirm(\'Confirma a remoção de ' . htmlspecialchars($restaurante['nome']) . '?\')">Remover</a>';
                echo '  </div>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo "<p>Você não tem restaurantes registados ou associados.</p>";
        }

    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erro ao listar restaurantes: Falha na comunicação com a base de dados.</p>";
        // Em ambiente de produção, não mostrar $e->getMessage()
    }
    ?>

</main>

<footer>
    <p>&copy; 2025 eatEasy.</p>
</footer>
</body>
</html>