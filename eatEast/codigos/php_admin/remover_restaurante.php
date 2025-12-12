<?php
session_start();
// Proteção de Página e método
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['user_type'] !== 'admin' || $_SERVER["REQUEST_METHOD"] !== "GET") {
    header("Location: login_admin.php");
    exit;
}

require '../DataBase/db-connect.php';

$id_restaurante = $_GET['id'] ?? null;
$id_admin_logado = $_SESSION['user_id'];

if (empty($id_restaurante)) {
    $_SESSION['msg_admin'] = "❌ Erro: ID do restaurante não fornecido.";
    header("Location: pag_inicial_admin.php");
    exit;
}

try {
    // 1. Verificar se o restaurante pertence a este Admin
    $sql_check = "SELECT id_admin_proprietario, nome FROM restaurante WHERE id_restaurante = :id";
    $stmt_check = $db->prepare($sql_check);
    $stmt_check->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
    $stmt_check->execute();
    $restaurante_info = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$restaurante_info || $restaurante_info['id_admin_proprietario'] != $id_admin_logado) {
        $_SESSION['msg_admin'] = "❌ Erro: Não tem permissão para remover este restaurante.";
        header("Location: pag_inicial_admin.php");
        exit;
    }

    // Inicia a transação
    $db->beginTransaction();

    // 2. Remover todas as reservas e ligações (M:M) antes de remover o restaurante principal
    // (Poderia usar CASCADE na DB, mas remover manualmente é mais seguro para logs/erros)

    // Remover Reservas associadas
    $sql_reserva = "DELETE FROM reserva WHERE id_restaurante = :id";
    $stmt_reserva = $db->prepare($sql_reserva);
    $stmt_reserva->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
    $stmt_reserva->execute();

    // Remover Ligações (Tipo de Cozinha)
    $sql_ligacao = "DELETE FROM restaurante_tipo_cozinha WHERE id_restaurante = :id";
    $stmt_ligacao = $db->prepare($sql_ligacao);
    $stmt_ligacao->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
    $stmt_ligacao->execute();

    // 3. Remover o Restaurante Principal
    $sql_delete = "DELETE FROM restaurante WHERE id_restaurante = :id";
    $stmt_delete = $db->prepare($sql_delete);
    $stmt_delete->bindParam(':id', $id_restaurante, PDO::PARAM_INT);
    $stmt_delete->execute();

    $db->commit();
    $_SESSION['msg_admin'] = "✅ Restaurante '{$restaurante_info['nome']}' removido com sucesso, incluindo todas as reservas e ligações associadas.";

} catch (PDOException $e) {
    $db->rollBack();
    $_SESSION['msg_admin'] = "❌ Erro fatal ao remover restaurante: " . $e->getMessage();
}

header("Location: pag_inicial_admin.php");
exit;
