<?php
session_start();
if(!isset($_SESSION['id_usuario'])){
    header("Location: ../login.php");
    exit;
}
require_once './../conexion/conexion.php';

if(!isset($_GET['id_sala'])){
    die("No se ha especificado la sala");
}
$id_sala = (int)$_GET['id_sala'];

// Obtener información de la sala (nombre y tipo)
$stmt_sala = $conn->prepare("SELECT nombre_sala FROM tbl_salas WHERE id_sala=:id_sala");
$stmt_sala->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$stmt_sala->execute();
$sala_info = $stmt_sala->fetch(PDO::FETCH_ASSOC);
if(!$sala_info){
    die("Sala no encontrada");
}

// Obtener mesas de la sala
$stmt = $conn->prepare("SELECT id_mesa,num_sillas,estado,tipo_mesa FROM tbl_mesas WHERE id_sala=:id_sala ORDER BY id_mesa");
$stmt->bindParam(':id_sala',$id_sala,PDO::PARAM_INT);
$stmt->execute();
$mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
<title>Gestión de Mesas - <?= htmlspecialchars($sala_info['nombre_sala']) ?></title>
<link rel="stylesheet" href="./../css/style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header class="header-flex">
    <div class="header-left">
        <form action="panel.php" method="get">
            <button type="submit" class="btn-volver">&#8592; Volver</button>
        </form>
    </div>

    <h1>Gestión de Mesas - <?= htmlspecialchars($sala_info['nombre_sala']) ?></h1>

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
    <div class="mesas-container">
        <?php foreach($mesas as $mesa): ?>
        <form method="post" class="mesa-form" data-id-mesa="<?= $mesa['id_mesa'] ?>">
            <button type="submit" class="mesa-btn <?= $mesa['estado']==='ocupada' ? 'ocupada' : 'libre' ?>">
                Mesa <?= $mesa['id_mesa'] ?><br>
                <?= $mesa['num_sillas'] ?> sillas<br>
                <?= ucfirst($mesa['tipo_mesa']) ?>
            </button>
        </form>
        <?php endforeach; ?>
    </div>
</main>

<script>
const idSala = <?= $id_sala ?>;
</script>
<script src="./../js/sweetalerts.js"></script>
<script src="./../js/validaciones.js"></script>
</body>
</html>
