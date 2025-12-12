<?php
session_start();

// Proteção de Página (Obrigatório)
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'cliente') {
    $_SESSION['login_error'] = "Acesso restrito. Faça login primeiro.";
    header("Location: login.php");
    exit;
}

// Garanta que o caminho para o db-connect.php está correto
require '../DataBase/db-connect.php';

$nome_cliente = $_SESSION['user_nome'];

// Recolhe e limpa o termo de pesquisa
$search_query = $_GET['query'] ?? '';
// O termo de pesquisa é convertido para minúsculas e envolvido em % para busca LIKE case-insensitive
$search_query_like = '%' . strtolower(trim($search_query)) . '%';

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Resultados da Pesquisa - eatEasy</title>
</head>
<body>
<header>
    <h1>eatEasy</h1>

    <form action="pesquisar_restaurante.php" method="GET">
        <input type="search" name="query" placeholder="Pesquisar por nome ou tipo de cozinha..." value="<?php echo htmlspecialchars($search_query); ?>" required>
        <button type="submit">🔍</button>
    </form>

    <div style="float: right;">
        <span>Nome Cliente: **<?php echo htmlspecialchars($nome_cliente); ?>**</span>
        <a href="logout.php">Sair (Logout)</a> |
        <a href="minhas_reservas.php">As minhas reservas</a> |
        <a href="pag_inicial_cliente.php">Voltar à Lista</a>
    </div>
    <div style="clear: both;"></div>
</header>

<main>
    <h2>Resultados da Pesquisa para: "<?php echo htmlspecialchars($search_query); ?>"</h2>

    <div class="restaurantes-listagem">

        <?php
        // A Consulta SQL: Pesquisa por NOME do Restaurante OU por TIPO DE COZINHA (designacao)
        $sql_search = "
                SELECT 
                    r.id_restaurante, 
                    r.nome, 
                    r.local_morada, 
                    r.preco_medio,
                    -- STRING_AGG concatena os tipos de cozinha num único campo
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
            // O mesmo parâmetro é ligado duas vezes (para o nome e para a designação)
            $stmt_search->bindParam(':search_query_like', $search_query_like);
            $stmt_search->execute();
            $restaurantes = $stmt_search->fetchAll(PDO::FETCH_ASSOC);

            if (count($restaurantes) > 0) {
                foreach ($restaurantes as $restaurante) {

                    $tipos_cozinha_str = $restaurante['tipos_cozinha'] ?? 'N/A';

                    // Apresentação do Cartão do Restaurante
                    echo '<div class="restaurante-card" style="border: 1px solid #ccc; padding: 10px; margin: 10px; display: inline-block; width: 250px;">';
                    echo '    <div class="foto" style="width: 100%; height: 150px; background-color: #eee; text-align: center; line-height: 150px;">[foto]</div>';
                    echo '    <h4>' . htmlspecialchars($restaurante['nome']) . '</h4>';
                    echo '    <p>Localização: ' . htmlspecialchars($restaurante['local_morada']) . '</p>';
                    echo '    <p>Tipo comida: ' . htmlspecialchars($tipos_cozinha_str) . '</p>';
                    echo '    <p>Preço médio por prato: ' . htmlspecialchars($restaurante['preco_medio']) . '€</p>';
                    // Link para a próxima funcionalidade
                    echo '    <a href="fazer_reserva.php?id=' . $restaurante['id_restaurante'] . '">Reservar</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>Nenhum restaurante encontrado com os critérios de pesquisa fornecidos.</p>";
            }
        } catch (PDOException $e) {
            // Em caso de erro de DB, mostra a mensagem
            echo "<p style='color: red;'>Erro na pesquisa: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
</main>

<footer>
    <p>&copy; 2025 eatEasy. Todos os direitos reservados.</p>
</footer>
</body>
</html>