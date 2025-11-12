<?php
require_once './../conexion/conexion.php';
session_start();

if(!isset($_SESSION['id_usuario'])) die("No autenticado");
if(!isset($_GET['id_mesa'])) die("ID de mesa no especificado");

$id_mesa = (int)$_GET['id_mesa'];
$id_usuario = $_SESSION['id_usuario'];

try {
    $conn->beginTransaction();

    // Actualizamos el estado de la mesa
    $stmt = $conn->prepare("UPDATE tbl_mesas SET estado='libre' WHERE id_mesa=:id");
    $stmt->bindParam(':id', $id_mesa, PDO::PARAM_INT);
    $stmt->execute();

    // Actualizamos la ocupación existente (la última con fecha_liberacion NULL)
    $log = $conn->prepare("
        UPDATE tbl_ocupaciones
        SET fecha_liberacion = NOW()
        WHERE id_mesa = :m AND fecha_liberacion IS NULL
        ORDER BY fecha_ocupacion DESC
        LIMIT 1
    ");
    $log->bindParam(':m', $id_mesa, PDO::PARAM_INT);
    $log->execute();

    $conn->commit();

    // Redirigir a la sala actual
    $id_sala = (int)$_GET['id_sala'] ?? 1;
    header("Location: sala.php?id_sala=$id_sala");
} catch(Exception $e) {
    $conn->rollBack();
    die("Error: ".$e->getMessage());
}
?>
