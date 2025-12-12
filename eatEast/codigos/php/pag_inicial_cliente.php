<?php
session_start();

// Lógica de Proteção de Página
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'cliente') {
    $_SESSION['login_error'] = "Acesso restrito. Faça login primeiro.";
    header("Location: login.php");
    exit;
}

// Inclui a conexão à base de dados
require '../DataBase/db-connect.php';

// Dados do cliente logado
$id_cliente = $_SESSION['user_id'];
$nome_cliente = $_SESSION['user_nome'];

// Consulta SQL para obter os dados de listagem
$sql_restaurantes_info = "
    SELECT 
        r.id_restaurante, 
        r.nome, 
        r.local_morada, 
        r.preco_medio,
        STRING_AGG(tc.designacao, ', ') AS tipos_cozinha
    FROM restaurante r
    LEFT JOIN restaurante_tipo_cozinha rtc ON r.id_restaurante = rtc.id_restaurante
    LEFT JOIN tipo_cozinha tc ON rtc.id_tipo_cozinha = tc.id_tipo_cozinha
    GROUP BY r.id_restaurante, r.nome, r.local_morada, r.preco_medio
    ORDER BY r.nome;
";
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial - eatEasy</title>
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
                <input type="search" name="query" placeholder="Pesquisar por nome ou tipo de cozinha..." required>
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
    <h2>Faça já a sua reserva</h2>

    <div class="restaurantes-grid">

        <?php
        try {
            $stmt_restaurantes = $db->query($sql_restaurantes_info);
            $restaurantes = $stmt_restaurantes->fetchAll(PDO::FETCH_ASSOC);

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
                echo "<p>Nenhum restaurante disponível para reserva.</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erro ao listar restaurantes: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2025 eatEasy. Todos os direitos reservados.</p>
        <div class="footer-links">
            <a href="#">Sobre Nós</a>
            <a href="#">Termos de Utilização</a>
        </div>
    </div>
</footer>
</body>
</html>