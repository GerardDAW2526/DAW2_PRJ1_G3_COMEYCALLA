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
                <!-- <div class="svg-content"> -->
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.<path d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z"/></svg> -->
                <!-- </div> -->
                <a class="texto" id="btn-iniciar-sesion">Iniciar sesión</a>
                <!-- <div class="svg-content"> -->
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.<path d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z"/></svg> -->
                <!-- </div> -->
            </div>



        </div>

        <!-- Contenedor del contenido -->
        <div class="content" id="content">

            <div class="left" id="left">

                <!-- Imagen -->
                <img src="../media/perfil.png" alt="No se ha podido cargar la imagen">



                <!-- Texto -->
                <h2>Nuestras excusas vienen con guarnición.</h2>


            </div>
    

            <div class="right" id="right">

                <form action="../proc/login.proc.php" method="POST" class="formulario">

                    <h1>Iniciar sesión</h1>

                    <div>
                        <label for="nombre">Usuario</label> <br>
                        <input type="text" name="nombre" id="nombre" class="nombre" placeholder="Introduzca el nombre del usuario...">
                    </div>

                    <br>

                    <div>
                        <label for="contra">Contraseña</label> <br>
                        <input type="text" name="contra" id="contra" class="contra" placeholder="Introduzca la contraseña...">
                    </div>

                    <br><br>

                    <div class="content-enviar">
                        <input type="submit" name="enviar" id="enviar" class="enviar" value="Entrar">
                    </div>
                    
                </form>

            </div>


        </div>

    </div>


    <div class="left-2" id="left-2">

        <!-- Imagen -->
        <img src="../media/perfil.png" alt="No se ha podido cargar la imagen">



        <!-- Texto -->
        <h2>Nuestras excusas vienen con guarnición.</h2>


    </div>

    


    <!-- Archivo less (una extension de CSS) -->
    <link rel="stylesheet/less" type="text/css" href="../estilos/estilos_login.less" />

    <!-- Recogida del código de la página principal de less (su configuración para su respectivo funcionamiento) -->
    <script src="https://cdn.jsdelivr.net/npm/less" ></script>


    <!-- Script JS -->
    <script src="../js/animaciones.js"></script>

</body>
</html>