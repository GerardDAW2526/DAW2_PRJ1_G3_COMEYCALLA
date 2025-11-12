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
    $stmt = $conn->prepare("UPDATE tbl_mesas SET estado='ocupada' WHERE id_mesa=:id");
    $stmt->bindParam(':id', $id_mesa, PDO::PARAM_INT);
    $stmt->execute();

    // Insertamos una sola vez en ocupaciones con fecha_liberacion NULL
    $log = $conn->prepare("
        INSERT INTO tbl_ocupaciones (id_mesa, id_usuario, fecha_ocupacion)
        VALUES (:m, :u, NOW())
    ");
    $log->bindParam(':m', $id_mesa);
    $log->bindParam(':u', $id_usuario);
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
