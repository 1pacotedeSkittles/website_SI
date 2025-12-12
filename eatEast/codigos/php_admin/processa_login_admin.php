<?php
session_start();
// O caminho deve ser: Subir para /php/, depois subir para /eatEasyInc/, e finalmente descer para /DataBase/
require '../DataBase/db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email'] ?? '');
    $password_inserida = $_POST['password'] ?? '';

    $sql = "SELECT id_admin, nome, password_hash FROM admin WHERE email = :email";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // 1. Verifica se o admin existe E 2. Se a password está correta
        if ($admin && password_verify($password_inserida, $admin['password_hash'])) {

            // Login com sucesso!
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['user_id'] = $admin['id_admin'];
            $_SESSION['user_nome'] = $admin['nome'];
            $_SESSION['user_type'] = 'admin';

            // Redireciona para a página de gestão do administrador (Criaremos a seguir)
            header("Location: pag_inicial_admin.php");
            exit;

        } else {
            // Email ou Password inválidos
            $_SESSION['admin_login_error'] = "Email ou Password inválidos.";
            header("Location: login_admin.php");
            exit;
        }

    } catch (PDOException $e) {
        // Erro fatal de DB
        $_SESSION['admin_login_error'] = "Ocorreu um erro no servidor durante o login: [DB ERROR]";
        // Não mostrar $e->getMessage() ao utilizador final, apenas ao debug
        header("Location: login_admin.php");
        exit;
    }
} else {
    header("Location: login_admin.php");
    exit;
}
?>