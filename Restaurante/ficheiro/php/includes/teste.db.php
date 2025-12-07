<?php
// Define que o ficheiro não tem limite de tempo de execução
set_time_limit(0);
// Exibe todos os erros (útil para debug)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Incluir o ficheiro de conexão
// O caminho deve ser ajustado consoante onde guardou 'teste_db.php'
// Se guardou em php/pages/, use: require '../includes/database.connect.php';
// Se guardou em php/, use: require './includes/database.connect.php';
require './database.connect.php';

echo "<h1>Teste de Conexão à Base de Dados PostgreSQL</h1>";

if (isset($pdo) && $pdo instanceof PDO) {
    try {
        // 2. Tentar executar uma consulta simples (para provar que a conexão funciona)
        $stmt = $pdo->query('SELECT version()');
        $version = $stmt->fetchColumn();

        echo "<p style='color: green; font-weight: bold;'>✅ SUCESSO! Conexão ao PostgreSQL estabelecida com sucesso.</p>";
        echo "<p>Versão do PostgreSQL: " . htmlspecialchars($version) . "</p>";
        echo "<p>O objeto \$pdo está pronto para ser usado.</p>";

        // 3. Testar a existência da tabela 'cliente' (Opcional, mas útil)
        $table_check = $pdo->query("SELECT EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = 'cliente')");
        if ($table_check->fetchColumn()) {
            echo "<p style='color: blue;'>✔️ Tabela 'cliente' encontrada na base de dados 'eatEast'.</p>";
        } else {
            echo "<p style='color: orange; font-weight: bold;'>⚠️ AVISO: A tabela 'cliente' NÃO foi encontrada. O registo e o login FALHARÃO até a criar.</p>";
        }

    } catch (PDOException $e) {
        // Se a conexão foi estabelecida mas a consulta falhou (ex: BD em manutenção)
        echo "<p style='color: red; font-weight: bold;'>❌ ERRO: Conexão bem-sucedida, mas consulta falhou.</p>";
        echo "<p>Detalhe do Erro: " . $e->getMessage() . "</p>";
    }
} else {
    // Esta parte só deve ser executada se o 'die()' dentro do database.connect.php falhar.
    echo "<p style='color: red; font-weight: bold;'>❌ FALHA CRÍTICA! O objeto \$pdo não foi criado.</p>";
}
?>