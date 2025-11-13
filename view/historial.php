<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit;
}

require_once './../conexion/conexion.php';

// Obtener todas las salas para el select
$stmt_salas = $conn->query("SELECT id_sala, nombre_sala FROM tbl_salas ORDER BY id_sala");
$salas_disponibles = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

// Inicializamos filtros
$filtros = [];
$params = [];

// Filtrado si se envía formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar'])) {

    // Fecha desde y hasta
    if (!empty($_GET['fecha_desde'])) {
        $fecha_fin = !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : $_GET['fecha_desde'];
        $filtros[] = "fecha_ocupacion BETWEEN :fecha_desde AND :fecha_hasta";
        $params[':fecha_desde'] = $_GET['fecha_desde'] . " 00:00:00";
        $params[':fecha_hasta'] = $fecha_fin . " 23:59:59";
    }

    // Hora desde y hasta
    if (!empty($_GET['hora_desde'])) {
        $hora_fin = !empty($_GET['hora_hasta']) ? $_GET['hora_hasta'] : "23:59:59";
        $filtros[] = "TIME(fecha_ocupacion) BETWEEN :hora_desde AND :hora_hasta";
        $params[':hora_desde'] = $_GET['hora_desde'];
        $params[':hora_hasta'] = $hora_fin;
    }

    // Camarero
    if (!empty($_GET['camarero'])) {
        $filtros[] = "u.nombre_completo LIKE :camarero";
        $params[':camarero'] = "%" . $_GET['camarero'] . "%";
    }

    // Tipo de mesa
    if (!empty($_GET['tipo_mesa'])) {
        $filtros[] = "m.tipo_mesa = :tipo_mesa";
        $params[':tipo_mesa'] = $_GET['tipo_mesa'];
    }

    // Número de mesa
    if (!empty($_GET['num_mesa'])) {
        $filtros[] = "m.id_mesa = :num_mesa";
        $params[':num_mesa'] = $_GET['num_mesa'];
    }

    // Sala
    if (!empty($_GET['sala'])) {
        $filtros[] = "s.id_sala = :sala";
        $params[':sala'] = $_GET['sala'];
    }
}

// Construimos la consulta
$sql = "SELECT 
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
        INNER JOIN tbl_usuarios u ON o.id_usuario = u.id_usuario";

if (count($filtros) > 0) {
    $sql .= " WHERE " . implode(" AND ", $filtros);
}

$sql .= " ORDER BY o.fecha_ocupacion DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$ocupaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Ocupaciones</title>
    <link rel="stylesheet" href="./../css/style.css">
    <script src="./../js/validaciones.js"></script>
</head>
<body>
<header style="position: relative;">
    <!-- Botón de volver arriba a la izquierda -->
    <a href="panel.php" class="btn-volver" style="position:absolute; left:0; top:0; margin:10px;">&#8592; Volver</a>
    <h1 style="text-align:center;">Historial de Mesas</h1>
</header>
<main>
    <form method="get" class="form-filtros" id="formFiltros">
        <!-- Fila 1 -->
        <div class="filtro-col">
            <label>Fecha inicio:</label>
            <input type="date" name="fecha_desde" value="<?= htmlspecialchars($_GET['fecha_desde'] ?? '') ?>" max="2100-12-31">
        </div>
        <div class="filtro-col">
            <label>Hora inicio:</label>
            <input type="time" name="hora_desde" value="<?= htmlspecialchars($_GET['hora_desde'] ?? '') ?>">
        </div>
        <div class="filtro-col">
            <label>Sala:</label>
            <select name="sala">
                <option value="">-- Todas --</option>
                <?php foreach($salas_disponibles as $sala_option): ?>
                    <option value="<?= $sala_option['id_sala'] ?>" <?= (isset($_GET['sala']) && $_GET['sala']==$sala_option['id_sala']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sala_option['nombre_sala']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filtro-col">
            <label>Tipo de mesa:</label>
            <select name="tipo_mesa">
                <option value="">-- Todos --</option>
                <option value="cuadrada" <?= (isset($_GET['tipo_mesa']) && $_GET['tipo_mesa']=='cuadrada') ? 'selected':'' ?>>Cuadrada</option>
                <option value="rectangular" <?= (isset($_GET['tipo_mesa']) && $_GET['tipo_mesa']=='rectangular') ? 'selected':'' ?>>Rectangular</option>
                <option value="redonda" <?= (isset($_GET['tipo_mesa']) && $_GET['tipo_mesa']=='redonda') ? 'selected':'' ?>>Redonda</option>
                <option value="especial" <?= (isset($_GET['tipo_mesa']) && $_GET['tipo_mesa']=='especial') ? 'selected':'' ?>>Especial</option>
            </select>
        </div>

        <!-- Fila 2 -->
        <div class="filtro-col">
            <label>Fecha final:</label>
            <input type="date" name="fecha_hasta" value="<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>" max="2100-12-31">
        </div>
        <div class="filtro-col">
            <label>Hora final:</label>
            <input type="time" name="hora_hasta" value="<?= htmlspecialchars($_GET['hora_hasta'] ?? '') ?>">
        </div>
        <div class="filtro-col">
            <label>Número de mesa:</label>
            <input type="number" name="num_mesa" min="1" value="<?= htmlspecialchars($_GET['num_mesa'] ?? '') ?>">
        </div>
        <div class="filtro-col">
            <label>Camarero:</label>
            <input type="text" name="camarero" placeholder="Nombre camarero" value="<?= htmlspecialchars($_GET['camarero'] ?? '') ?>">
        </div>

        <!-- Botones invertidos ocupando todo el ancho -->
        <div class="filtro-botones" style="grid-column:1/-1; display:flex; gap:10px;">
            <button type="submit" name="borrar" style="flex:1;">Borrar filtros</button>
            <button type="submit" name="buscar" id="btnBuscar" style="flex:1;">Buscar</button>
        </div>
    </form>

    <table class="tabla-historial">
        <thead>
            <tr>
                <th>Mesa</th>
                <th>Tipo de mesa</th>
                <th>Nº Sillas</th>
                <th>Sala</th>
                <th>Camarero</th>
                <th>Fecha y hora ocupación</th>
                <th>Fecha y hora liberación</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($ocupaciones) === 0): ?>
                <tr><td colspan="7" style="text-align:center;">No se encontraron registros</td></tr>
            <?php else: ?>
                <?php foreach($ocupaciones as $o): ?>
                    <tr>
                        <td><?= htmlspecialchars($o['id_mesa']) ?></td>
                        <td><?= htmlspecialchars($o['tipo_mesa']) ?></td>
                        <td><?= htmlspecialchars($o['num_sillas']) ?></td>
                        <td><?= htmlspecialchars($o['nombre_sala']) ?></td>
                        <td><?= htmlspecialchars($o['camarero']) ?></td>
                        <td><?= htmlspecialchars($o['fecha_ocupacion']) ?></td>
                        <td><?= $o['fecha_liberacion'] ?? 'Aún ocupada' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
