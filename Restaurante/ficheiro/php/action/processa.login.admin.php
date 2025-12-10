<?php
session_start();
require "../includes/bd.php"; // AJUSTAR PARA A TUA LIGAÇÃO

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = trim($_POST["id_trabalho"]);
    $password = trim($_POST["password_admin"]);

    if (empty($id) || empty($password)) {
        $_SESSION['admin_erro'] = "Preencha todos os campos.";
        header("Location: loginAdmin.php");
        exit();
    }

    // Procurar admin
    $stmt = $conn->prepare("SELECT * FROM admin WHERE id_trabalho = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        $_SESSION['admin_erro'] = "ID de trabalho incorreto.";
        header("Location: loginAdmin.php");
        exit();
    }

    $admin = $resultado->fetch_assoc();

    // verificar password
    if (!password_verify($password, $admin['password'])) {
        $_SESSION['admin_erro'] = "Palavra-passe incorreta.";
        header("Location: loginAdmin.php");
        exit();
    }

    // Guardar sessão admin
    $_SESSION['admin_id'] = $admin['id_trabalho'];
    $_SESSION['admin_nome'] = $admin['nome'];

    header("Location: dashboardAdmin.php");
    exit();
}
