<?php
session_start();

// Proteção de Página
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['admin_login_error'] = "Acesso negado. Faça login como Administrador.";
    header("Location: login_admin.php");
    exit;
}

require '../DataBase/db-connect.php';

$id_admin = $_SESSION['user_id'];
$nome_admin = $_SESSION['user_nome'];

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração - eatEasy</title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/minhas_reservas.css"> </head>
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
    <h2>Painel de Administração</h2>

    <?php
    // 1. Mensagens de sucesso ou erro (após Adicionar/Remover)
    if (isset($_SESSION['msg_admin'])) {
        $cor = str_contains($_SESSION['msg_admin'], 'sucesso') ? 'green' : 'red';
        echo '<p style="color: ' . $cor . '; font-weight: bold;">' . $_SESSION['msg_admin'] . '</p>';
        unset($_SESSION['msg_admin']);
    }
    ?>

    <p class="admin-actions">
        <a href="adicionar_restaurante.php">Adicionar Novo Restaurante</a> |
        <a href="lista_reservas.php">Ver Reservas para Meus Restaurantes</a>
    </p>

    <hr>

    <h3>Os Seus Restaurantes</h3>

    <div class="restaurantes-gestao-list">
        <?php
        // Consulta: Listar Restaurantes geridos por este Admin
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
                    // Usar 'restaurante-item' para aplicar o estilo de lista de gestão
                    echo '<li class="restaurante-item">';
                    echo '  <strong>' . htmlspecialchars($restaurante['nome']) . '</strong> (ID: ' . $restaurante['id_restaurante'] . ')<br>';
                    echo '  Local: ' . htmlspecialchars($restaurante['local_morada']) . ' | ';
                    echo '  Preço Médio: ' . htmlspecialchars($restaurante['preco_medio']) . '€<br>';

                    // Botões de Ação
                    echo '  <div class="actions">';
                    echo '      <a href="atualizar_restaurante.php?id=' . $restaurante['id_restaurante'] . '">Atualizar</a>';
                    echo '      <a href="remover_restaurante.php?id=' . $restaurante['id_restaurante'] . '" onclick="return confirm(\'Confirma a remoção de ' . htmlspecialchars($restaurante['nome']) . '?\')">Remover</a>';
                    echo '  </div>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo "<p>Você não tem restaurantes registados ou associados. <a href='adicionar_restaurante.php'>Adicionar um agora?</a></p>";
            }

        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erro ao listar restaurantes: Falha na comunicação com a base de dados.</p>";
        }
        ?>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2025 eatEasy. Todos os direitos reservados.</p>
    </div>
</footer>
</body>
</html>