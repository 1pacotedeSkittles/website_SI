<?php
session_start();

// Proteção de Página
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'cliente') {
    header("Location: login.php");
    exit;
}

require '../DataBase/db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recolha e limpeza dos dados
    $id_cliente = $_POST['id_cliente'] ?? null;
    $id_restaurante = $_POST['id_restaurante'] ?? null;
    $data = $_POST['data'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $num_pessoas = $_POST['pessoas'] ?? 0;

    // Validação básica
    if (empty($id_restaurante) || empty($data) || empty($hora) || $num_pessoas <= 0) {
        $_SESSION['reserva_mensagem'] = "❌ Erro: Por favor, preencha todos os campos corretamente.";
        header("Location: fazer_reserva.php?id=$id_restaurante");
        exit;
    }

    // Combinação de data e hora para o formato TIMESTAMP do PostgreSQL
    $data_hora_reserva = $data . ' ' . $hora;

    // Verificação de Data Futura
    if (strtotime($data_hora_reserva) < time()) {
        $_SESSION['reserva_mensagem'] = "❌ Erro: A data e hora da reserva devem ser futuras.";
        header("Location: fazer_reserva.php?id=$id_restaurante");
        exit;
    }

    // Query de Inserção (id_reserva e data_registo usam SERIAL/DEFAULT)
    $sql = "INSERT INTO reserva (id_cliente, id_restaurante, data_hora_reserva, num_pessoas, status_reserva) 
            VALUES (:id_cliente, :id_restaurante, :data_hora, :num_pessoas, 'Pendente')"; // 'Pendente' é o default, mas é bom ser explícito

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->bindParam(':id_restaurante', $id_restaurante, PDO::PARAM_INT);
        $stmt->bindParam(':data_hora', $data_hora_reserva);
        $stmt->bindParam(':num_pessoas', $num_pessoas, PDO::PARAM_INT);

        $stmt->execute();

        $_SESSION['reserva_mensagem'] = "✅ Reserva efetuada com sucesso! Aguarda confirmação do restaurante.";

        // Redireciona para a página de listagem de reservas
        header("Location: minhas_reservas.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['reserva_mensagem'] = "❌ Erro ao registar reserva: " . $e->getMessage();
        header("Location: fazer_reserva.php?id=$id_restaurante");
        exit;
    }
} else {
    // Se não for POST, redireciona
    header("Location: pag_inicial_cliente.php");
    exit;
}
?>