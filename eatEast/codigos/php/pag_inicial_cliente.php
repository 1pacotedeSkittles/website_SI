<?php
session_start();

// 1. Lógica de Proteção de Página
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'cliente') {
    $_SESSION['login_error'] = "Acesso restrito. Faça login primeiro.";
    header("Location: login.php");
    exit;
}

// Inclui a conexão à base de dados
// Garanta que o caminho para o ficheiro db-connect.php está correto.
require '../DataBase/db-connect.php';

// Dados do cliente logado
$id_cliente = $_SESSION['user_id'];
$nome_cliente = $_SESSION['user_nome'];

// 2. Consulta Otimizada: Buscar todos os restaurantes e os seus tipos de cozinha
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
</head>
<body>
<header>
    <h1>eatEasy</h1>

    <form action="pesquisar_restaurante.php" method="GET">
        <input type="search" name="query" placeholder="Pesquisar por nome ou tipo de cozinha..." required>
        <button type="submit">🔍</button>
    </form>

    <div style="float: right;">
        <span>Nome Cliente: **<?php echo htmlspecialchars($nome_cliente); ?>**</span>
        <a href="logout.php">Sair (Logout)</a> |
        <a href="minhas_reservas.php">As minhas reservas</a>
    </div>
    <div style="clear: both;"></div>
</header>

<main>
    <h2>Faça já a sua reserva</h2>

    <div class="restaurantes-listagem">

        <?php
        try {
            $stmt_restaurantes = $db->query($sql_restaurantes_info);
            $restaurantes = $stmt_restaurantes->fetchAll(PDO::FETCH_ASSOC);

            if (count($restaurantes) > 0) {
                foreach ($restaurantes as $restaurante) {

                    // Garante que o tipo de cozinha é exibido corretamente (pode ser NULL se não tiver)
                    $tipos_cozinha_str = $restaurante['tipos_cozinha'] ?? 'N/A';

                    // Apresentação do Cartão do Restaurante (Baseado no mockup)
                    echo '<div class="restaurante-card" style="border: 1px solid #ccc; padding: 10px; margin: 10px; display: inline-block; width: 250px;">';
                    echo '    <div class="foto" style="width: 100%; height: 150px; background-color: #eee; text-align: center; line-height: 150px;">[foto]</div>';
                    echo '    <h4>' . htmlspecialchars($restaurante['nome']) . '</h4>';
                    echo '    <p>Localização: ' . htmlspecialchars($restaurante['local_morada']) . '</p>';
                    echo '    <p>Tipo comida: ' . htmlspecialchars($tipos_cozinha_str) . '</p>';
                    echo '    <p>Preço médio por prato: ' . htmlspecialchars($restaurante['preco_medio']) . '€</p>';
                    echo '    <a href="fazer_reserva.php?id=' . $restaurante['id_restaurante'] . '">Reservar</a>';
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
    <p>&copy; 2025 eatEasy. Todos os direitos reservados.</p>
</footer>
</body>
</html>