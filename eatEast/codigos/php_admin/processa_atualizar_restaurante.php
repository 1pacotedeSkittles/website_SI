<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['user_type'] !== 'admin' || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login_admin.php");
    exit;
}

require '../DataBase/db-connect.php';

// 1. Recolha dos dados
$id_restaurante = $_POST['id_restaurante'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$local_morada = trim($_POST['local_morada'] ?? '');
$preco_medio = $_POST['preco_medio'] ?? 0;
$tipos_cozinha = $_POST['tipos_cozinha'] ?? []; // Array de IDs de cozinha
$id_admin_logado = $_SESSION['user_id'];

if (empty($id_restaurante) || empty($nome) || empty($local_morada) || $preco_medio <= 0) {
    $_SESSION['msg_admin'] = "❌ Erro: Campos obrigatórios em falta.";
    header("Location: pag_inicial_admin.php");
    exit;
}

try {
    // 2. Verificar permissão (Segurança)
    $sql_check = "SELECT id_admin_proprietario FROM restaurante WHERE id_restaurante = :id";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
    $stmt_check->execute();
    $proprietario = $stmt_check->fetchColumn();

    if ($proprietario != $id_admin_logado) {
        $_SESSION['msg_admin'] = "❌ Erro: Não tem permissão para atualizar este restaurante.";
        header("Location: pag_inicial_admin.php");
        exit;
    }

    $db->beginTransaction();

    // 3. Atualizar a tabela Restaurante (dados simples)
    $sql_update_rest = "UPDATE restaurante SET nome = :nome, local_morada = :morada, preco_medio = :preco 
                        WHERE id_restaurante = :id";
    $stmt_update_rest = $db->prepare($sql_update_rest);
    $stmt_update_rest->bindParam(':nome', $nome);
    $stmt_update_rest->bindParam(':morada', $local_morada);
    $stmt_update_rest->bindParam(':preco', $preco_medio);
    $stmt_update_rest->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
    $stmt_update_rest->execute();

    // 4. Atualizar a tabela de Ligação (M:M)

    // 4.1. ELIMINAR todas as ligações existentes para este restaurante
    $sql_delete_links = "DELETE FROM restaurante_tipo_cozinha WHERE id_restaurante = :id";
    $stmt_delete_links = $db->prepare($sql_delete_links);
    $stmt_delete_links->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
    $stmt_delete_links->execute();

    // 4.2. INSERIR as novas ligações selecionadas
    if (!empty($tipos_cozinha)) {
        $sql_insert_link = "INSERT INTO restaurante_tipo_cozinha (id_restaurante, id_tipo_cozinha) 
                            VALUES (:id_restaurante, :id_tipo_cozinha)";
        $stmt_insert_link = $db->prepare($sql_insert_link);

        foreach ($tipos_cozinha as $id_tipo) {
            $stmt_insert_link->bindParam(':id_restaurante', $id_restaurante, PDO::PARAM_INT);
            $stmt_insert_link->bindParam(':id_tipo_cozinha', $id_tipo, PDO::PARAM_INT);
            $stmt_insert_link->execute();
        }
    }

    $db->commit();
    $_SESSION['msg_admin'] = "✅ Restaurante '$nome' atualizado com sucesso!";

} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['msg_admin'] = "❌ Erro ao atualizar restaurante: " . $e->getMessage();
}

header("Location: pag_inicial_admin.php");
exit;
?>