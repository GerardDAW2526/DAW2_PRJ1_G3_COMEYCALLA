<?php
/* 
  Bloque: inicio de sesión y carga de dependencias
  - session_start(): inicia/continúa la sesión PHP para almacenar id_usuario si login OK.
  - require_once validaciones.login.php: carga funciones de validación y la conexión ($conn).
  - Variables iniciales: $errors y $old_username para almacenar errores por campo y el valor
    del usuario que se conservará en el input si está correctamente escrito.
*/
session_start();
// Cargamos las funciones de validación (esta incluye la conexión)
require_once __DIR__ . '/view/validaciones_login.php';

$errors = [];
$old_username = '';

/* 
  Bloque: procesamiento del formulario (POST)
  - Lee $_POST['username'] y $_POST['password'] (asegurando valores por defecto).
  - Primero valida formato de usuario con validar_usuario_formato() para evitar consultas innecesarias.
  - Si formato OK: consulta existencia con obtener_usuario_por_nombre() para decidir si mantener
    el username en el formulario tras un fallo de contraseña.
  - Llama a validar_login() que devuelve ['success'=>bool,'errors'=>array,'user'=>array|null].
  - Si éxito: establece $_SESSION con id_usuario y nombre_completo y redirige a panel.php.
  - Si hay errores: los coloca en $errors para mostrarlos bajo cada campo en HTML.
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 1) Validación de formato en servidor (evita consultar BDD si formato inválido)
    $formatError = validar_usuario_formato($username);
    if ($formatError !== null) {
        $errors['usuario'] = $formatError;
        $old_username = ''; // no conservar si formato inválido
    } else {
        // 2) Si formato OK, comprobar existencia para decidir si conservar username en el campo
        $user_exists = obtener_usuario_por_nombre($username);
        $old_username = $user_exists ? $username : '';

        // 3) Validación completa (existencia + contraseña)
        $result = validar_login($username, $password);
        if ($result['success']) {
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $_SESSION['id_usuario'] = $result['user']['id_usuario'] ?? $result['user']['id'] ?? null;
            $_SESSION['nombre_completo'] = $result['user']['nombre_completo'] ?? null;
            header("Location: ./view/panel.php");
            exit;
        } else {
            $errors = $result['errors'];
        }
    }
} else {
    // GET / reinicio -> limpiar campos
    $old_username = '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Restaurante</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        /* Bloque: estilos inline mínimos para errores y foco inválido */
        .field-error { color: #c00; font-size: 0.9em; margin-top: 4px; min-height: 1.1em; }
        input.invalid { outline: 2px solid rgba(200,0,0,0.2); }
    </style>
</head>
<body>
<header>
    <h1>Acceso al Sistema</h1>
</header>

<main class="main-registro">
    <!-- 
      Bloque: formulario HTML
      - action apunta a este mismo archivo (login.php) para procesar en el servidor.
      - autocomplete="off" y autocomplete="new-password" para evitar que el navegador
        autocompletar la contraseña rescriba el comportamiento deseado.
      - Se muestran los errores por campo renderizados desde $errors (servidor).
      - El campo usuario conserva $old_username solo cuando corresponde.
    -->
    <form id="loginForm" action="login.php" method="POST" class="form-registro" autocomplete="off" novalidate>
        <label for="username">Usuario:</label><br>
        <input id="username" type="text" name="username" value="<?php echo htmlspecialchars($old_username); ?>" required autocomplete="off"><br>
        <div class="field-error" id="error-username"><?php echo htmlspecialchars($errors['usuario'] ?? ''); ?></div>
        <br>

        <label for="password">Contraseña:</label><br>
        <input id="password" type="password" name="password" autocomplete="new-password" required><br>
        <div class="field-error" id="error-password"><?php echo htmlspecialchars($errors['contrasena'] ?? ''); ?></div>
        <br>

        <button type="submit">Entrar</button>
    </form>
</main>

<p class="link-login">
    ¿No tienes cuenta? 
    <a href="registro.php">Registrarse</a>
</p>

<script>
(function(){
    /*
      Bloque: validación cliente (JavaScript)
      - validateUsuarioClient: comprueba vacío, longitud y que no contenga números.
      - validatePassClient: comprueba que la contraseña no esté vacía.
      - Se ejecutan en blur (al perder foco) para mostrar errores debajo del campo,
        y al submit para impedir envío si fallan las comprobaciones cliente.
      - Nota: la validación del servidor sigue siendo la fuente de la verdad.
    */
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    const errUser = document.getElementById('error-username');
    const errPass = document.getElementById('error-password');
    const form = document.getElementById('loginForm');

    function validateUsuarioClient() {
        const v = username.value.trim();
        if (v === '') {
            errUser.textContent = 'El campo usuario no puede estar vacío.';
            username.classList.add('invalid');
            return false;
        }
        if (v.length < 3) {
            errUser.textContent = 'El usuario debe tener al menos 3 caracteres.';
            username.classList.add('invalid');
            return false;
        }
        if (v.length > 50) {
            errUser.textContent = 'El usuario no puede tener más de 50 caracteres.';
            username.classList.add('invalid');
            return false;
        }
        // Restricción: no permitir números en el usuario (cliente)
        if (/\d/.test(v)) {
            errUser.textContent = 'El usuario no puede contener números.';
            username.classList.add('invalid');
            return false;
        }
        errUser.textContent = '';
        username.classList.remove('invalid');
        return true;
    }

    function validatePassClient() {
        const v = password.value;
        if (v === '') {
            errPass.textContent = 'La contraseña no puede estar vacía.';
            password.classList.add('invalid');
            return false;
        }
        errPass.textContent = '';
        password.classList.remove('invalid');
        return true;
    }

    username.addEventListener('blur', validateUsuarioClient);
    password.addEventListener('blur', validatePassClient);

    form.addEventListener('submit', function(e){
        const okU = validateUsuarioClient();
        const okP = validatePassClient();
        if (!okU || !okP) {
            e.preventDefault();
        }
        // Si pasan verificaciones básicas, el servidor validará existencia/contraseña.
    });
})();
</script>
</body>
</html>
