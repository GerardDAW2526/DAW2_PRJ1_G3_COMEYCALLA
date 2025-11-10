<?php
// proc/filtrar_ocupaciones.php

header('Content-Type: application/json');
require_once '../includes/sesion.php';    // Verifica sesión activa
require_once '../includes/funciones.php'; // Funciones reutilizables

// Filtrado opcional
$id_sala = isset($_POST['id_sala']) && !empty($_POST['id_sala']) ? intval($_POST['id_sala']) : null;
$id_mesa = isset($_POST['id_mesa']) && !empty($_POST['id_mesa']) ? intval($_POST['id_mesa']) : null;

try {
    // Obtener ocupaciones usando la función centralizada
    $ocupaciones = getOcupaciones($conn, $id_sala, $id_mesa);

    echo json_encode([
        'success' => true,
        'data' => $ocupaciones
    ]);

} catch (Exception $e) {
    error_log("Error al filtrar ocupaciones: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'msg' => 'Error al obtener las ocupaciones.'
    ]);
}
?>
