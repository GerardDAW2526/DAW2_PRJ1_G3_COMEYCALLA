<?php
// proc/liberar_mesa.php

header('Content-Type: application/json');
require_once '../includes/sesion.php';    // Verifica sesión activa
require_once '../includes/funciones.php'; // Funciones reutilizables

// Verificar que se haya recibido el ID de la mesa
if (!isset($_POST['id_mesa']) || empty($_POST['id_mesa'])) {
    echo json_encode(['success' => false, 'msg' => 'No se recibió ID de la mesa.']);
    exit;
}

$id_mesa = intval($_POST['id_mesa']);
$id_usuario = $_SESSION['id_usuario']; // Usuario que realiza la acción

// Llamada a la función que libera la mesa
$result = liberarMesa($conn, $id_mesa, $id_usuario);

if ($result) {
    echo json_encode(['success' => true, 'msg' => 'Mesa liberada correctamente.']);
} else {
    echo json_encode(['success' => false, 'msg' => 'No se pudo liberar la mesa. Puede que ya esté libre.']);
}
?>
