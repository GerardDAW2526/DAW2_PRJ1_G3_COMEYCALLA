<?php
// ========================
// Validaciones PHP para historial.php
// ========================

// Inicializamos variables para errores y filtros seguros
$errores = [];
$filtros = [];
$params = [];

// ========================
// Fecha inicio
// ========================
if (!empty($_GET['fecha_desde'])) {
    $fecha_desde = $_GET['fecha_desde'];

    // Validamos formato YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_desde)) {
        $errores[] = "Formato de fecha inicio inválido";
    } else {
        // Validamos año máximo 2100 y mínimo 1900
        $anio = (int)substr($fecha_desde, 0, 4);
        if ($anio < 1900 || $anio > 2100) {
            $errores[] = "El año de fecha inicio debe estar entre 1900 y 2100";
        } elseif (!checkdate((int)substr($fecha_desde, 5, 2), (int)substr($fecha_desde, 8, 2), $anio)) {
            $errores[] = "Fecha inicio no es válida";
        } else {
            $params[':fecha_desde'] = $fecha_desde . " 00:00:00";
        }
    }
}

// ========================
// Fecha fin
// ========================
if (!empty($_GET['fecha_hasta'])) {
    $fecha_hasta = $_GET['fecha_hasta'];

    // Validamos formato YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_hasta)) {
        $errores[] = "Formato de fecha final inválido";
    } else {
        $anio = (int)substr($fecha_hasta, 0, 4);
        if ($anio < 1900 || $anio > 2100) {
            $errores[] = "El año de fecha final debe estar entre 1900 y 2100";
        } elseif (!checkdate((int)substr($fecha_hasta, 5, 2), (int)substr($fecha_hasta, 8, 2), $anio)) {
            $errores[] = "Fecha final no es válida";
        } else {
            $params[':fecha_hasta'] = $fecha_hasta . " 23:59:59";
        }
    }
}

// ========================
// Ajuste automático si fecha_hasta no se proporciona
// ========================
if (!empty($params[':fecha_desde']) && empty($params[':fecha_hasta'])) {
    $params[':fecha_hasta'] = $params[':fecha_desde'];
}

// ========================
// Hora inicio
// ========================
if (!empty($_GET['hora_desde'])) {
    $hora_desde = $_GET['hora_desde'];
    if (!preg_match('/^\d{2}:\d{2}$/', $hora_desde)) {
        $errores[] = "Formato de hora inicio inválido";
    } else {
        list($h, $m) = explode(':', $hora_desde);
        if ($h < 0 || $h > 23 || $m < 0 || $m > 59) {
            $errores[] = "Hora inicio no válida";
        } else {
            $params[':hora_desde'] = $hora_desde . ":00";
        }
    }
}

// ========================
// Hora fin
// ========================
if (!empty($_GET['hora_hasta'])) {
    $hora_hasta = $_GET['hora_hasta'];
    if (!preg_match('/^\d{2}:\d{2}$/', $hora_hasta)) {
        $errores[] = "Formato de hora final inválido";
    } else {
        list($h2, $m2) = explode(':', $hora_hasta);
        if ($h2 < 0 || $h2 > 23 || $m2 < 0 || $m2 > 59) {
            $errores[] = "Hora final no válida";
        } else {
            $params[':hora_hasta'] = $hora_hasta . ":59";
        }
    }
}

// ========================
// Ajuste automático si hora_hasta no se proporciona
// ========================
if (!empty($params[':hora_desde']) && empty($params[':hora_hasta'])) {
    $params[':hora_hasta'] = "23:59:59";
}

// ========================
// Número de mesa
// ========================
if (!empty($_GET['num_mesa'])) {
    $num_mesa = $_GET['num_mesa'];
    if (!filter_var($num_mesa, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        $errores[] = "Número de mesa inválido";
    } else {
        $params[':num_mesa'] = (int)$num_mesa;
    }
}

// ========================
// Tipo de mesa
// ========================
$tipos_validos = ['cuadrada','rectangular','redonda','especial'];
if (!empty($_GET['tipo_mesa'])) {
    if (!in_array($_GET['tipo_mesa'], $tipos_validos)) {
        $errores[] = "Tipo de mesa inválido";
    } else {
        $params[':tipo_mesa'] = $_GET['tipo_mesa'];
    }
}

// ========================
// Sala
// ========================
if (!empty($_GET['sala'])) {
    if (!filter_var($_GET['sala'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]])) {
        $errores[] = "Sala inválida";
    } else {
        $params[':sala'] = (int)$_GET['sala'];
    }
}

// ========================
// Camarero
// ========================
if (!empty($_GET['camarero'])) {
    $camarero = trim($_GET['camarero']);
    if (strlen($camarero) > 100) {
        $errores[] = "Nombre de camarero demasiado largo";
    } else {
        $params[':camarero'] = "%$camarero%";
    }
}

// ========================
// Resultado final
// ========================
return ['errores' => $errores, 'params' => $params];
?>