<?php
require_once '../conexion/conexion.php';
session_start();


// die();

if(!isset($_SESSION['id_usuario'])) die("No autenticado");
if(!isset($_GET['id_mesa'])) die("ID de mesa no especificado");
if(!isset($_GET['id_sala'])) die("ID de sala no especificado");

$id_mesa = $_GET['id_mesa'];
$id_sala = $_GET['id_sala'];
$id_usuario = $_SESSION['id_usuario'];

try {

    $conn->beginTransaction();

    // Actualizar estado de la mesa a libre
    $stmt = $conn->prepare("UPDATE tbl_mesas SET estado='libre' WHERE id_mesa=:id");
    $stmt->bindParam(':id',$id_mesa,PDO::PARAM_INT);
    $stmt->execute();

    // Registrar liberación en histórico
    $log = $conn->prepare("INSERT INTO tbl_ocupaciones (id_mesa,id_usuario,fecha_ocupacion,fecha_liberacion) VALUES (:m,:u,NOW(),NOW())");
    $log->bindParam(':m',$id_mesa);
    $log->bindParam(':u',$id_usuario);
    $log->execute();

    $conn->commit();

    // Redirigir a la misma sala
    // header("Location: sala.php?id_sala={$id_sala}");
    // exit;

    if($id_sala = 1){
        header("Location: ../view/sala.php?completado");
        exit();
    } else if($id_sala = 2){
        header("Location: ../view/sala-2.php?completado");
        exit();
    } else if($id_sala = 3){
        header("Location: ../view/sala-vip-1.php?completado");
        exit();
    } else if($id_sala = 4){
        header("Location: ../view/sala-vip-2.php?completado");
        exit();
    } else if($id_sala = 5){
        header("Location: ../view/sala-vip-3.php?completado");
        exit();
    } else if($id_sala = 6){
        header("Location: ../view/sala-terraza-1.php?completado");
        exit();
    } else if($id_sala = 7){
        header("Location: ../view/sala-terraza-2.php?completado");
        exit();
    } else if($id_sala = 8){
        header("Location: ../view/sala-terraza-3.php?completado");
        exit();
    }

} catch(Exception $e) {
    $conn->rollBack();
    die("Error: ".$e->getMessage());
}
?>