<?php

session_start();
require_once '../conexion/conexion.php';

$error = "";

// ========================
// Procesamiento del login
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // if(!(isset($_POST['nombre']))){

    //     $error = "No puedes dejar el campo usuario vacío.";

    // } else if(!(isset($_POST['contra']))){

    //     $error = "No puedes dejar el campo contraseña vacío.";

    // }

    $username = trim($_POST['nombre']);
    $password = trim($_POST['contra']);

    // Consulta del usuario en la base de datos
    $stmt = $conn->prepare("SELECT * FROM tbl_usuarios WHERE username = :u");
    $stmt->bindParam(':u', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificación de contraseña
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['nombre_completo'] = $user['nombre_completo'];
        header("Location: ./sala.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Come & Calla</title>
</head>
<body>
    
    <!-- El contenedor que envuelve toda la estructura -->

    <div class="contenedor-principal">


        <!-- Header, dónde irá el botón de iniciar sesión -->
        <div class="header">


            <!-- Botón de iniciar sesión -->
            <div href="./login.php" class="btn-login">
                <a class="texto" id="btn-iniciar-sesion">Iniciar sesión</a>
            </div>



        </div>

        <!-- Contenedor del contenido (este contenido es invisible, se mostrará al hacer clic en el botón iniciar sesión)-->
        <div class="content" id="content">

            <!-- Apartado izquierdo -->
            <div class="left" id="left">

                <!-- Imagen -->
                <img src="../media/Logo.png" alt="No se ha podido cargar la imagen">



                <!-- Texto -->
                <!-- <h1>Come y Calla</h1> -->
                <h3>Nuestras excusas vienen con guarnición.</h3>


            </div>
    

            <!-- Apartado derecho -->
            <div class="right" id="right">


                <!-- Creamos el formulario para enviar y verificar los datos -->
                <form action="" method="POST" class="formulario">

                    <h1>Iniciar sesión</h1>

                    <!-- Creamos diferentes contenedores dónde estarán los inputs -->
                    <div>
                        <label for="nombre">Usuario</label> <br>
                        <input type="text" name="nombre" id="nombre" class="nombre" placeholder="Introduzca el nombre del usuario...">

                        <!-- Usuario incorrecto -->
                        <p class="error" id="errorUser"><?php if(isset($_GET['error']) && $_GET['error'] == 1){echo "Usuario incorrecto.";} echo $error; ?></p>
                    </div>

                    <br>

                    <div>
                        <label for="contra">Contraseña</label> <br>
                        <input type="password" name="contra" id="contra" class="contra" placeholder="Introduzca la contraseña...">

                        <!-- Contraseña incorrecta -->
                        <p class="error" id="errorContra"><?php if(isset($_GET['error']) && $_GET['error'] == 2){echo "Contraseña incorrecta.";} echo $error ?></p>
                    </div>

                    <br><br>

                    <div class="content-enviar">
                        <input type="submit" name="enviar" id="enviar" class="enviar" value="Entrar">
                    </div>
                    
                </form>

            </div>


        </div>

    </div>



    <!-- Este content está implementado para la primera visita, será un contenedor fijo que se mostrará al principio de todo, mediante el clic
    del botón iniciar sesión se ocultará. -->
    <div class="left-2" id="left-2">

        <!-- Imagen -->
        <img src="../media/Logo.png" alt="No se ha podido cargar la imagen">



        <!-- Texto -->
        <!-- <h1>Come y Calla</h1> -->
        <h3>Nuestras excusas vienen con guarnición.</h3>


    </div>

    


    <!-- Archivo less (una extension de CSS) -->
    <link rel="stylesheet/less" type="text/css" href="../estilos/estilos_login.less" />

    <!-- Recogida del código de la página principal de less (su configuración para su respectivo funcionamiento) -->
    <script src="https://cdn.jsdelivr.net/npm/less" ></script>


    <!-- Script JS -->
    <script src="../js/animaciones.js"></script>
    <script src="../js/validaciones.js"></script>

</body>
</html>