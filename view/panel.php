<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../login.php");
    exit;
}
require_once './../conexion/conexion.php';

// Obtener salas
$stmt = $conn->query("SELECT id_sala, nombre_sala FROM tbl_salas ORDER BY id_sala");
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener usuario logueado
$stmt2 = $conn->prepare("SELECT nombre_completo FROM tbl_usuarios WHERE id_usuario=:id");
$stmt2->bindParam(':id', $_SESSION['id_usuario']);
$stmt2->execute();
$usuario = $stmt2->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Principal</title>
<link rel="stylesheet" href="./../css/style.css">
</head>
<body>
<header class="header-flex">
    <div class="header-left">
        <form action="panel.php" method="get">
            <button type="button" class="btn-volver">&#8592; Volver</button>
        </form>
    </div>

    <h1>Bienvenido al Panel</h1>

    <div class="header-right">
        <form action="historial.php" method="get">
            <button type="submit" class="btn-buscar">&#128269;</button>
        </form>
        <span class="user-name"><?= htmlspecialchars($usuario['nombre_completo']) ?></span>
        <form action="../logout.php" method="post">
            <button type="submit" class="btn-cerrar-sesion">Cerrar Sesión</button>
        </form>
    </div>
</header>

<main>
    <h2 class="main-title">Selecciona una sala</h2>
    <div class="mesas-container">
        <?php foreach($salas as $sala): ?>
        <form action="sala.php" method="get">
            <input type="hidden" name="id_sala" value="<?= $sala['id_sala'] ?>">
            <button type="submit" class="mesa-btn libre"><?= htmlspecialchars($sala['nombre_sala']) ?></button>
        </form>
        <?php endforeach; ?>
    </div>
</main>
</body>
</html>
