<?php
// Não inicia a sessão aqui, pois a página é pública.
require '../DataBase/db-connect.php';

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
    <title>Bem-vindo ao eatEasy</title>

    <link rel="stylesheet" href="../css/tipografia.css">
    <link rel="stylesheet" href="../css/nav_footer.css">
    <link rel="stylesheet" href="../css/pag_inicial_cliente.css">
</head>
<body>

<header>
    <a href="index.php" class="logo">eatEasy</a>

    <div class="nav-area">
        <div class="search-bar">
            <form action="../php/pesquisar_restaurante.php" method="GET">
                <input type="search" name="query" placeholder="Pesquisar por nome ou tipo de cozinha..." required>
                <button type="submit">🔍</button>
            </form>
        </div>

        <div class="nav-links">
            <a href="#">Contactos</a>
            <a href="../php/login.php">Login</a>
            <a href="../php/registo.php">Registar</a>
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
                    // O link de reserva redireciona para a página de login
                    echo '        <a href="php/cliente/login.php" class="btn-reservar">Reservar (Faça Login)</a>';
                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo "<p>Nenhum restaurante disponível para reserva.</p>";
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
        <div class="footer-links">
            <a href="#">Sobre Nós</a>
            <a href="#">Termos de Utilização</a>
        </div>
    </div>
</footer>
</body>
</html>