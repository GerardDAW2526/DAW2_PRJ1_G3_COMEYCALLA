<?php
require_once './../conexion/conexion.php';
session_start();

if(!isset($_SESSION['id_usuario'])){
    header("Location: ../login.php");
    exit;
}

// Variables de filtros
$filtros = [];
$where = [];

// Campos de filtro
if (!empty($_GET['fecha_ocupacion'])) {
    $where[] = "DATE(o.fecha_ocupacion) = :fecha_ocupacion";
    $filtros[':fecha_ocupacion'] = $_GET['fecha_ocupacion'];
}
if (!empty($_GET['fecha_liberacion'])) {
    $where[] = "DATE(o.fecha_liberacion) = :fecha_liberacion";
    $filtros[':fecha_liberacion'] = $_GET['fecha_liberacion'];
}
if (!empty($_GET['hora_ocupacion'])) {
    $where[] = "TIME(o.fecha_ocupacion) = :hora_ocupacion";
    $filtros[':hora_ocupacion'] = $_GET['hora_ocupacion'];
}
if (!empty($_GET['hora_liberacion'])) {
    $where[] = "TIME(o.fecha_liberacion) = :hora_liberacion";
    $filtros[':hora_liberacion'] = $_GET['hora_liberacion'];
}
if (!empty($_GET['tipo_mesa'])) {
    $where[] = "m.tipo_mesa = :tipo_mesa";
    $filtros[':tipo_mesa'] = $_GET['tipo_mesa'];
}
if (!empty($_GET['num_mesa'])) {
    $where[] = "m.id_mesa = :num_mesa";
    $filtros[':num_mesa'] = $_GET['num_mesa'];
}
if (!empty($_GET['sala'])) {
    $where[] = "s.id_sala = :sala";
    $filtros[':sala'] = $_GET['sala'];
}
if (!empty($_GET['camarero'])) {
    $where[] = "u.nombre_completo LIKE :camarero";
    $filtros[':camarero'] = "%".$_GET['camarero']."%";
}

$sql = "
SELECT 
    m.id_mesa,
    m.tipo_mesa,
    m.num_sillas,
    s.nombre_sala,
    u.nombre_completo AS camarero,
    o.fecha_ocupacion,
    o.fecha_liberacion
FROM tbl_ocupaciones o
INNER JOIN tbl_mesas m ON o.id_mesa = m.id_mesa
INNER JOIN tbl_salas s ON m.id_sala = s.id_sala
INNER JOIN tbl_usuarios u ON o.id_usuario = u.id_usuario
";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY o.fecha_ocupacion DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($filtros);
$historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial de Ocupaciones</title>
<link rel="stylesheet" href="./../css/style.css">
</head>
<body>

<header class="header-flex">
    <div class="header-left">
        <form action="panel.php" method="get">
            <button type="submit" class="btn-volver">&#8592; Volver</button>
        </form>
    </div>

    <h1>Historial de Ocupaciones</h1>

    <div class="header-right">
        <form action="../logout.php" method="post">
            <button type="submit" class="btn-cerrar-sesion">Cerrar Sesión</button>
        </form>
    </div>
</header>

<main>
    <h2 class="main-title">Filtrar resultados</h2>

    <form method="get" class="filtros-form">
        <div class="filtros-grid">

            <!-- Fechas -->
            <div class="filtro-col">
                <label>Fecha Ocupación</label>
                <input type="date" name="fecha_ocupacion" value="<?= htmlspecialchars($_GET['fecha_ocupacion'] ?? '') ?>">
                <label>Fecha Liberación</label>
                <input type="date" name="fecha_liberacion" value="<?= htmlspecialchars($_GET['fecha_liberacion'] ?? '') ?>">
            </div>

            <!-- Horas -->
            <div class="filtro-col">
                <label>Hora Ocupación</label>
                <input type="time" name="hora_ocupacion" value="<?= htmlspecialchars($_GET['hora_ocupacion'] ?? '') ?>">
                <label>Hora Liberación</label>
                <input type="time" name="hora_liberacion" value="<?= htmlspecialchars($_GET['hora_liberacion'] ?? '') ?>">
            </div>

            <!-- Mesas -->
            <div class="filtro-col">
                <label>Tipo de Mesa</label>
                <input type="text" name="tipo_mesa" placeholder="Interior / Terraza" value="<?= htmlspecialchars($_GET['tipo_mesa'] ?? '') ?>">
                <label>Nº Mesa</label>
                <input type="number" name="num_mesa" min="1" value="<?= htmlspecialchars($_GET['num_mesa'] ?? '') ?>">
            </div>

            <!-- Camarero y Sala -->
            <div class="filtro-col">
                <label>Camarero</label>
                <input type="text" name="camarero" placeholder="Nombre camarero" value="<?= htmlspecialchars($_GET['camarero'] ?? '') ?>">
                <label>Sala</label>
                <input type="number" name="sala" min="1" value="<?= htmlspecialchars($_GET['sala'] ?? '') ?>">
            </div>
        </div>

        <div class="filtros-botonera">
            <button type="submit" class="btn-primario">Buscar</button>
            <a href="historial.php" class="btn-secundario">Limpiar</a>
        </div>
    </form>

    <h2 class="main-title">Resultados</h2>

    <table class="tabla-historial">
        <thead>
            <tr>
                <th>Mesa</th>
                <th>Tipo de Mesa</th>
                <th>Nº Sillas</th>
                <th>Sala</th>
                <th>Camarero</th>
                <th>Fecha Ocupación</th>
                <th>Fecha Liberación</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($historial) > 0): ?>
                <?php foreach($historial as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['id_mesa']) ?></td>
                        <td><?= htmlspecialchars($fila['tipo_mesa']) ?></td>
                        <td><?= htmlspecialchars($fila['num_sillas']) ?></td>
                        <td><?= htmlspecialchars($fila['nombre_sala']) ?></td>
                        <td><?= htmlspecialchars($fila['camarero']) ?></td>
                        <td><?= htmlspecialchars($fila['fecha_ocupacion']) ?></td>
                        <td>
                            <?= $fila['fecha_liberacion'] ? htmlspecialchars($fila['fecha_liberacion']) : '<span class="ocupada">Aún ocupada</span>' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="sin-resultados">No se encontraron resultados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
