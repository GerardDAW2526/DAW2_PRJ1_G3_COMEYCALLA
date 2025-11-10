<?php
// proc/login.proc.php
session_start();
require_once '../conexion/conexion.php';

// Verificar que se hayan enviado los datos del formulario
if (!isset($_POST['nombre'], $_POST['contra'])) {
    header('Location: ../view/login.php?error=1');
    exit;
}

$usuario = trim($_POST['nombre']);
$password = trim($_POST['contra']);

try {
    // Preparar consulta para obtener usuario por username
    $stmt = $conn->prepare("SELECT id_usuario, username, nombre_completo, password FROM tbl_usuarios WHERE username = :usuario");
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Verificar contraseña
        if (password_verify($password, $row['password'])) {

            // Crear sesión segura
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['nombre_completo'] = $row['nombre_completo'];
            $_SESSION['ultimo_acceso'] = time();

            // Redirigir a home.php
            header('Location: ../view/home.php');
            exit;

        } else {
            // Contraseña incorrecta
            header('Location: ../view/principal.php?error=2');
            exit;
        }
    } else {
        // Usuario no encontrado
        header('Location: ../view/principal.php?error=3');
        exit;
    }

} catch (PDOException $e) {
    // Error de base de datos
    error_log("Error login: " . $e->getMessage());
    header('Location: ../view/error.php');
    exit;
}
?>
