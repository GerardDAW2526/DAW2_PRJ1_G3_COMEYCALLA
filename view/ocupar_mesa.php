<?php
require_once './../conexion/conexion.php';
session_start();

if(!isset($_SESSION['id_usuario'])) die("No autenticado");
if(!isset($_GET['id_mesa'])) die("ID de mesa no especificado");
if(!isset($_GET['id_sala'])) die("ID de sala no especificado");

$id_mesa = (int)$_GET['id_mesa'];
$id_sala = (int)$_GET['id_sala'];
$id_usuario = $_SESSION['id_usuario'];

try {
    $conn->beginTransaction();

    // Actualizar estado de la mesa a ocupada
    $stmt = $conn->prepare("UPDATE tbl_mesas SET estado='ocupada' WHERE id_mesa=:id");
    $stmt->bindParam(':id',$id_mesa,PDO::PARAM_INT);
    $stmt->execute();

    // Registrar ocupación en histórico
    $log = $conn->prepare("INSERT INTO tbl_ocupaciones (id_mesa,id_usuario,fecha_ocupacion) VALUES (:m,:u,NOW())");
    $log->bindParam(':m',$id_mesa);
    $log->bindParam(':u',$id_usuario);
    $log->execute();

    $conn->commit();

    // Redirigir a la misma sala
    header("Location: sala.php?id_sala={$id_sala}");
    exit;
} catch(Exception $e) {
    $conn->rollBack();
    die("Error: ".$e->getMessage());
}
?>
