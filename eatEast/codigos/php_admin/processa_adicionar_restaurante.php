<?php
session_start();
// Proteção de Página
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['user_type'] !== 'admin' || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login_admin.php");
    exit;
}

require '../DataBase/db-connect.php';

// Recolha dos dados
$id_admin = $_POST['id_admin'] ?? $_SESSION['user_id']; // Preferencialmente da sessão, se não vier do POST
$nome = trim($_POST['nome'] ?? '');
$local_morada = trim($_POST['local_morada'] ?? '');
$preco_medio = $_POST['preco_medio'] ?? 0;
$tipos_cozinha = $_POST['tipos_cozinha'] ?? []; // Array de IDs de cozinha

// 2. Validação básica
if (empty($nome) || empty($local_morada) || $preco_medio <= 0) {
    $_SESSION['msg_admin'] = "❌ Erro: Todos os campos obrigatórios devem ser preenchidos corretamente.";
    header("Location: adicionar_restaurante.php");
    exit;
}

try {
    // Inicia uma transação para garantir que ambas as inserções (restaurante e ligações) são atómicas
    $db->beginTransaction();

    // 3. Inserir o Restaurante principal
    $sql_rest = "INSERT INTO restaurante (nome, local_morada, preco_medio, id_admin_proprietario) 
                 VALUES (:nome, :local_morada, :preco_medio, :id_admin)";
    $stmt_rest = $db->prepare($sql_rest);
    $stmt_rest->bindParam(':nome', $nome);
    $stmt_rest->bindParam(':local_morada', $local_morada);
    $stmt_rest->bindParam(':preco_medio', $preco_medio);
    $stmt_rest->bindParam(':id_admin', $id_admin, PDO::PARAM_INT);
    $stmt_rest->execute();

    // Obter o ID do restaurante recém-inserido (Crucial!)
    // Esta função é específica do PostgreSQL para obter o último ID da sequência
    $id_restaurante = $db->lastInsertId('restaurante_id_restaurante_seq');

    // Inserir as Ligações Tipo_Cozinha (Tabela M:M)
    if (!empty($tipos_cozinha) && $id_restaurante) {
        $sql_ligacao = "INSERT INTO restaurante_tipo_cozinha (id_restaurante, id_tipo_cozinha) 
                        VALUES (:id_restaurante, :id_tipo_cozinha)";
        $stmt_ligacao = $db->prepare($sql_ligacao);

        foreach ($tipos_cozinha as $id_tipo) {
            $stmt_ligacao->bindParam(':id_restaurante', $id_restaurante, PDO::PARAM_INT);
            $stmt_ligacao->bindParam(':id_tipo_cozinha', $id_tipo, PDO::PARAM_INT);
            $stmt_ligacao->execute();
        }
    }

    //  Finalizar a Transação
    $db->commit();
    $_SESSION['msg_admin'] = "✅ Restaurante '$nome' registado com sucesso!";

} catch (PDOException $e) {
    // Se algo falhar, reverte todas as alterações (INSERT do restaurante e das ligações)
    $db->rollBack();
    $_SESSION['msg_admin'] = "❌ Erro ao registar restaurante: Falha na DB. " . $e->getMessage();
}

// Redireciona para o Painel para ver o novo restaurante
header("Location: pag_inicial_admin.php");
exit;
