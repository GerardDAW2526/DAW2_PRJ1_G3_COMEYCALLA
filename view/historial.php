<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./principal.php");
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


// --> Borrar filtros mediante GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['borrar'])) {

    $_GET['fecha_desde'] = "";
    $_GET['hora_desde'] = "";
    $_GET['camarero'] = "";
    $_GET['tipo_mesa'] = "";
    $_GET['num_mesa'] = "";
    $_GET['sala'] = "";

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
    <link rel="stylesheet" href="../estilos/estilos_historial.css">
    <!-- <script src="../js/validaciones.js"></script> -->
</head>
<body>
<div class="header">

        <!-- Imagen -->
        <img src="../media/Logo.png" alt="No se ha podido cargar la imagen">

        <!-- Título de la sala -->
        <h1>Comedor 1</h1>


        <div>

            <a href="./historial.php"><img src="" alt="No se ha podido cargar la imagen"></a>
            <form action="./logout.php" method="post">
                <button type="submit" class="btn-cerrar-sesion">Cerrar Sesión</button>
            </form>

        </div>

    </div>
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
        <div class="filtro-botones">
            <button type="submit" class="btn verde" name="buscar" id="btnBuscar"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z"/></svg></button>
            <button type="submit" class="btn rojo" name="borrar"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M232.7 69.9L224 96L128 96C110.3 96 96 110.3 96 128C96 145.7 110.3 160 128 160L512 160C529.7 160 544 145.7 544 128C544 110.3 529.7 96 512 96L416 96L407.3 69.9C402.9 56.8 390.7 48 376.9 48L263.1 48C249.3 48 237.1 56.8 232.7 69.9zM512 208L128 208L149.1 531.1C150.7 556.4 171.7 576 197 576L443 576C468.3 576 489.3 556.4 490.9 531.1L512 208z"/></svg></button>
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
                <tr><td colspan="7">No se encontraron registros</td></tr>
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

<div class="footer">

    <a href="./sala.php">Comedor 1</a>
    <a href="./sala-2.php">Comedor 2</a>
    <a href="./sala-vip-1.php">Sala VIP 1</a>
    <a href="./sala-vip-2.php">Sala VIP 2</a>
    <a href="./sala-vip-3.php">Sala VIP 3</a>
    <a href="./sala-terraza-1.php">Terraza 1</a>
    <a href="./sala-terraza-2.php">Terraza 2</a>
    <a href="./sala-terraza-3.php">Terraza 3</a>

</div>

<div class="footer-2" id="footer-2">

    <button class="btn-desplegable" id="btn-desplegable"><img src="../media/menu.png" alt=""></button>

    <div class="desplegable" id="desplegable">

        <div>
            <a href="./sala.php">Comedor 1</a><br><br><br>
            <a href="./sala-2.php">Comedor 2</a><br><br><br>
            <a href="./sala-vip-1.php">Sala VIP 1</a><br><br><br>
            <a href="./sala-vip-2.php">Sala VIP 2</a><br><br><br>
        </div>

        <div>

            <a href="./sala-vip-3.php">Sala VIP 3</a><br><br><br>
            <a href="./sala-terraza-1.php">Terraza 1</a><br><br><br>
            <a href="./sala-terraza-2.php">Terraza 2</a><br><br><br>
            <a href="./sala-terraza-3.php">Terraza 3</a><br><br><br>

        </div>


    </div>

</div>

    
    <!-- LESS -->
    <link rel="stylesheet/less" type="text/css" href="../estilos/estilos_salas.less" />
    <script src="https://cdn.jsdelivr.net/npm/less"></script>


</body>
</html>