<?php
session_start();
require_once './conexion/conexion.php';

$error = "";
$success = "";

// ========================
// Procesamiento del formulario
// ========================
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $nombre_completo = trim($_POST['nombre_completo']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validación PHP
    if(strlen($username) < 3 || strlen($username) > 50) {
        $error = "El nombre de usuario debe tener entre 3 y 50 caracteres";
    } elseif(strlen($nombre_completo) < 3) {
        $error = "El nombre completo es demasiado corto";
    } elseif($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } elseif(strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        // Comprobar si el usuario ya existe
        $stmt = $conn->prepare("SELECT id_usuario FROM tbl_usuarios WHERE username = :u");
        $stmt->bindParam(':u', $username);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $error = "El nombre de usuario ya existe";
        } else {
            // Insertar usuario en la base de datos
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $insert = $conn->prepare(
                "INSERT INTO tbl_usuarios (username,nombre_completo,password) VALUES (:u,:n,:p)"
            );
            $insert->bindParam(':u', $username);
            $insert->bindParam(':n', $nombre_completo);
            $insert->bindParam(':p', $hash);

            if($insert->execute()) {
                $success = "Usuario registrado correctamente. <a href='login.php'>Ir al login</a>";
            } else {
                $error = "Error al registrar usuario";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Restaurante</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="./js/validaciones.js"></script>
</head>
<body>
<header>
    <h1>Registro de nuevo usuario</h1>
</header>

<main class="main-registro">
    <!-- Formulario de registro -->
    <form method="POST" id="formRegistro" class="form-registro">
        <label>Nombre de usuario:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Nombre completo:</label><br>
        <input type="text" name="nombre_completo" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Confirmar contraseña:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Registrarse</button>
    </form>

    <!-- Mensajes de error o éxito -->
    <?php if($error): ?>
        <p class="error-msg"><?= $error ?></p>
    <?php endif; ?>
    <?php if($success): ?>
        <p class="success-msg"><?= $success ?></p>
    <?php endif; ?>

    <p class="link-login">
        <a href="./index.php">Volver al login</a>
    </p>
</main>
</body>
</html>