<?php

session_start();


if(!(isset($_SESSION['nombre_completo']))){

    header("Location: ../index.php");
    exit();

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sala 1</title> <!-- En la BBDD tendría el id 1 -->

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- LESS -->
    <link rel="stylesheet/less" type="text/css" href="../estilos/estilos_salas.less" />
    <script src="https://cdn.jsdelivr.net/npm/less"></script>

    <!-- SWEETALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <div class="header">
        <!-- Imagen -->
        <img src="../media/Logo.png" alt="No se ha podido cargar la imagen">

        <!-- Título de la sala -->
        <h1>Sala 1</h1>

    </div>


    <div class="content">

            <div class="left">

                <div class="section-1"> <!-- Esto sería un grid -->

                    <!-- Idea de, hacer un foreach, y por cada item que encuentre lo añade a la fila. -->

                    <?php

                        include_once "../conexion/conexion.php";

                        try {

                            $sql = "SELECT * FROM tbl_mesas WHERE id_sala = 1 AND tipo_mesa = 'rectangular'";
                            $stmt = $conn->query($sql);
                            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $i = 1;

                            // var_dump($resultados);

                            foreach($resultados as $mesa){

                                // echo $mesa['id_mesa'];

                                echo '<div class="mesa">';
                                echo '<form action="../index.php" method="POST">';
                                echo '<input type="hidden" id="id" class="id" name="id" value="' . $mesa['id_mesa'] . '">';
                                echo '<img src="../media/'. $mesa['tipo_mesa'] . '_' . $mesa['estado'] . '.png" alt="No se ha podido cargar la imagen" class="mesa-svg">';

                                if($mesa['estado'] == "ocupada"){
                                    echo '<a class="btn-mesa libre" id="' . $mesa['id_mesa'] . '" name="' . $mesa['id_sala'] . '">' . $mesa['id_mesa'] . '</a>';
                                } else if($mesa['estado'] == "libre"){
                                    echo '<a class="btn-mesa ocupada" id="' . $mesa['id_mesa'] . '" name="' . $mesa['id_sala'] . '">' . $mesa['id_mesa'] . '</a>';
                                }

                                echo '</form>';
                                echo '</div>';
                                // echo $mesa['estado'];

                                $i++;

                                if($i > 8){
                                    break;
                                }

                            }

                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }



                    ?>

                </div>

                <div class="section-2">
                    <!-- <p>hola</p> -->

                    <?php

                        include_once "../conexion/conexion.php";

                        try {

                            $sql2 = "SELECT * FROM tbl_mesas WHERE id_sala = 1 AND tipo_mesa = 'cuadrada'";
                            $stmt2 = $conn->query($sql2);
                            $resultados2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                            $i = 1;

                            // var_dump($resultados);

                            foreach($resultados2 as $mesa){

                                // echo $mesa['id_mesa'];

                                echo '<div class="mesa">';
                                echo '<form action="../index.php" method="POST">';
                                echo '<input type="hidden" id="id" class="id" name="id" value="' . $mesa['id_mesa'] . '">';
                                echo '<img src="../media/'. $mesa['tipo_mesa'] . '_' . $mesa['estado'] . '.png" alt="No se ha podido cargar la imagen" class="mesa-svg">';

                                if($mesa['estado'] == "ocupada"){
                                    echo '<a class="btn-mesa libre" id="' . $mesa['id_mesa'] . '" name="' . $mesa['id_sala'] . '">' . $mesa['id_mesa'] . '</a>';
                                } else if($mesa['estado'] == "libre"){
                                    echo '<a class="btn-mesa ocupada" id="' . $mesa['id_mesa'] . '" name="' . $mesa['id_sala'] . '">' . $mesa['id_mesa'] . '</a>';
                                }

                                echo '</form>';
                                echo '</div>';
                                // echo $mesa['estado'];

                                $i++;

                                if($i > 7){
                                    break;
                                }

                            }

                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }



                    ?>
                </div>

            </div>
            
            <div class="section-3">

                <?php

                    include_once "../conexion/conexion.php";

                    try {

                        $sql3 = "SELECT * FROM tbl_mesas WHERE id_sala = 1 AND tipo_mesa = 'redonda'";
                        $stmt3 = $conn->query($sql3);
                        $resultados3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

                        $i = 1;

                        // var_dump($resultados);

                        foreach($resultados3 as $mesa){

                            // echo $mesa['id_mesa'];

                            echo '<div class="mesa">';
                            echo '<form action="../index.php" method="POST">';
                            echo '<input type="hidden" id="id" class="id" name="id" value="' . $mesa['id_mesa'] . '">';
                            echo '<img src="../media/'. $mesa['tipo_mesa'] . '_' . $mesa['estado'] . '.png" alt="No se ha podido cargar la imagen" class="mesa-svg">';

                            if($mesa['estado'] == "ocupada"){
                                echo '<a class="btn-mesa libre" id="' . $mesa['id_mesa'] . '" name="' . $mesa['id_sala'] . '">' . $mesa['id_mesa'] . '</a>';
                            } else if($mesa['estado'] == "libre"){
                                echo '<a class="btn-mesa ocupada" id="' . $mesa['id_mesa'] . '" name="' . $mesa['id_sala'] . '">' . $mesa['id_mesa'] . '</a>';
                            }

                            echo '</form>';
                            echo '</div>';
                            // echo $mesa['estado'];

                            $i++;

                            if($i > 2){
                                break;
                            }

                        }

                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }



                    ?>

            </div>

    </div>


    <div class="footer">

        <a href="./sala.php">Sala 1</a>
        <a href="./sala-2.php">Sala 2</a>
        <a href="./sala-vip-1.php">Sala VIP 1</a>
        <a href="./sala-vip-2.php">Sala VIP 2</a>
        <a href="./sala-vip-3.php">Sala VIP 3</a>
        <a href="./sala-terraza-1.php">Terraza 1</a>
        <a href="./sala-terraza-2.php">Terraza 2</a>
        <a href="./sala-terraza-3.php">Terraza 3</a>

    </div>



    <!-- SCRIPT -->
    <script src="../js/sweetalerts.js"></script>

                
    <?php
        // if(isset($_GET['completado'])){

        //     echo "<script>alert('Se ha liberado correctamente')</script>";

        // }
        // if(isset($_GET['completado2'])){

        //     echo "<script>alert('Se ha ocupado correctamente')</script>";

        // }
    ?>

</body>
</html>