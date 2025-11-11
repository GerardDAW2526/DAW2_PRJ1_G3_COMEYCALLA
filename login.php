<?php
session_start();
require_once './conexion/conexion.php';

$error = "";

// ========================
// Procesamiento del login
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Consulta del usuario en la base de datos
    $stmt = $conn->prepare("SELECT * FROM tbl_usuarios WHERE username = :u");
    $stmt->bindParam(':u', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificación de contraseña
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['nombre_completo'] = $user['nombre_completo'];
        header("Location: ./view/panel.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Restaurante</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<header>
    <h1>Acceso al Sistema</h1>
</header>

<main class="main-registro">
    <!-- Formulario de login -->
    <form method="POST" class="form-registro">
        <label>Usuario:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>

    <!-- Mensaje de error -->
    <?php if($error): ?>
        <p class="error-msg"><?= $error ?></p>
    <?php endif; ?>
</main>

<p class="link-login">
    ¿No tienes cuenta? 
    <a href="registro.php">Registrarse</a>
</p>
</body>
</html>
