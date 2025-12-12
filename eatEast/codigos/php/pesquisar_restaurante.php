<?php
session_start();

// Proteção de Página
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'cliente') {
    $_SESSION['login_error'] = "Acesso restrito. Faça login primeiro.";
    header("Location: login.php");
    exit;
}

require '../DataBase/db-connect.php';

$nome_cliente = $_SESSION['user_nome'];
$search_query = $_GET['query'] ?? '';
$search_query_like = '%' . strtolower(trim($search_query)) . '%';

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Resultados da Pesquisa - eatEasy</title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/pag_inicial_cliente.css">
</head>
<body>

<header>
    <a href="pag_inicial_cliente.php" class="logo">eatEasy</a>

    <div class="nav-area">
        <div class="search-bar">
            <form action="pesquisar_restaurante.php" method="GET">
                <input type="search" name="query" placeholder="Pesquisar por nome ou tipo de cozinha..." value="<?php echo htmlspecialchars($search_query); ?>" required>
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
    <h2>Resultados da Pesquisa para: "<?php echo htmlspecialchars($search_query); ?>"</h2>

    <div class="restaurantes-grid">

        <?php
        // A Consulta SQL principal
        $sql_search = "
                SELECT 
                    r.id_restaurante, 
                    r.nome, 
                    r.local_morada, 
                    r.preco_medio,
                    STRING_AGG(tc.designacao, ', ') AS tipos_cozinha
                FROM restaurante r
                LEFT JOIN restaurante_tipo_cozinha rtc ON r.id_restaurante = rtc.id_restaurante
                LEFT JOIN tipo_cozinha tc ON rtc.id_tipo_cozinha = tc.id_tipo_cozinha
                
                WHERE LOWER(r.nome) LIKE :search_query_like
                OR LOWER(tc.designacao) LIKE :search_query_like
                
                GROUP BY r.id_restaurante, r.nome, r.local_morada, r.preco_medio
                ORDER BY r.nome;
            ";

        try {
            $stmt_search = $db->prepare($sql_search);
            $stmt_search->bindParam(':search_query_like', $search_query_like);
            $stmt_search->execute();
            $restaurantes = $stmt_search->fetchAll(PDO::FETCH_ASSOC);

            if (count($restaurantes) > 0) {
                foreach ($restaurantes as $restaurante) {

                    $tipos_cozinha_str = $restaurante['tipos_cozinha'] ?? 'N/A';

                    // ESTRUTURA DO CARTÃO DE RESTAURANTE
                    echo '<div class="restaurante-card">';
                    echo '    <div class="foto-placeholder">Foto</div>';
                    echo '    <div class="card-details">';
                    echo '        <h3>' . htmlspecialchars($restaurante['nome']) . '</h3>';
                    echo '        <p>Localização: ' . htmlspecialchars($restaurante['local_morada']) . '</p>';
                    echo '        <p>Tipo comida: ' . htmlspecialchars($tipos_cozinha_str) . '</p>';
                    echo '        <p>Preço médio por prato: <span>' . htmlspecialchars($restaurante['preco_medio']) . '€</span></p>';
                    echo '        <a href="fazer_reserva.php?id=' . $restaurante['id_restaurante'] . '" class="btn-reservar">Reservar</a>';
                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo "<p>Nenhum restaurante encontrado com os critérios de pesquisa fornecidos.</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erro na pesquisa: " . $e->getMessage() . "</p>";
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