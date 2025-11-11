<?php
require_once './../conexion/conexion.php';

// ========================
// Devolver estado de mesa en JSON
// ========================
if(isset($_GET['check'])){
    $id_mesa = (int)$_GET['id_mesa'];

    $stmt = $conn->prepare("SELECT estado FROM tbl_mesas WHERE id_mesa=:id");
    $stmt->bindParam(':id', $id_mesa, PDO::PARAM_INT);
    $stmt->execute();
    $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($mesa);
    exit;
}
?>
