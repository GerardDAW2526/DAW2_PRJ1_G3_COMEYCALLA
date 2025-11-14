<?php
// Cargar conexión usando ruta robusta basada en este archivo
$conexionPath = __DIR__ . '/../conexion/conexion.php';
if (!file_exists($conexionPath)) {
    throw new RuntimeException("No se encuentra conexion.php en: {$conexionPath}");
}
require_once $conexionPath;

/**
 * Devuelve el número de mesas con estado 'ocupada' para la sala indicada.
 * - $id_sala: entero
 * Retorna int (0 si error).
 */
function obtener_mesas_ocupadas(int $id_sala): int {
    global $conn;
    if (empty($conn) || !($conn instanceof PDO)) {
        return 0;
    }
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_mesas WHERE id_sala = :sala AND estado = 'ocupada'");
        $stmt->execute([':sala' => $id_sala]);
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        // opcional: loguear $e->getMessage()
        return 0;
    }
}
