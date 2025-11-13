<?php
// Recomendado (ya aplicado):
$conexionPath = __DIR__ . '/../conexion/conexion.php';
if (!file_exists($conexionPath)) {
    throw new RuntimeException("No se encuentra conexion.php en: {$conexionPath}");
}
require_once $conexionPath;

/**
 * Valida y comprueba credenciales de login.
 * - $pdo: instancia PDO conectada a tu BDD (obligatorio para comprobar existencia/contraseña).
 * - $usuario: string
 * - $contrasena: string
 *
 * Devuelve array:
 *  ['success' => bool, 'errors' => array, 'user' => array|null]
 */

// Función para obtener usuario por nombre (usa la conexión global $conn y la tabla real)
function obtener_usuario_por_nombre(string $usuario) {
	// Usamos la conexión global provista por conexion.php
	global $conn;
	// Si no hay conexión PDO válida devolvemos false para que el llamador lo gestione
	if (empty($conn) || !($conn instanceof PDO)) {
		return false;
	}
	// Preparar y ejecutar la consulta de forma segura
	$sql = "SELECT * FROM tbl_usuarios WHERE username = :u LIMIT 1";
	$stmt = $conn->prepare($sql);
	$stmt->execute([':u' => $usuario]);
	// Devolver fila asociativa o false si no existe
	return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
}

function validar_usuario_formato(string $usuario): ?string {
	// Eliminar espacios al principio y final
	$usuario = trim($usuario);

	// Comprobar que no esté vacío
	if ($usuario === '') {
		return 'El campo usuario no puede estar vacío.';
	}

	// Obtener longitud en caracteres multibyte
	$len = mb_strlen($usuario);

	// Comprobar longitud mínima
	if ($len < 3) {
		return 'El usuario debe tener al menos 3 caracteres.';
	}

	// Comprobar longitud máxima
	if ($len > 50) {
		return 'El usuario no puede tener más de 50 caracteres.';
	}

	// Prohibir dígitos en el nombre de usuario (no se permiten números)
	if (preg_match('/\d/', $usuario)) {
		return 'El usuario no puede contener números.';
	}

	// Si pasa todas las comprobaciones, devolver null (sin error)
	return null;
}

/**
 * Valida login.
 * - Usa la conexión global $conn
 * - Comprueba formato de usuario.
 * - Comprueba que el usuario exista en tbl_usuarios.
 * - Comprueba contraseña (campo 'password').
 */
function validar_login(string $usuario, string $contrasena): array {
	$errors = [];

	// 1) Formato usuario
	if ($err = validar_usuario_formato($usuario)) {
		$errors['usuario'] = $err;
		return ['success' => false, 'errors' => $errors, 'user' => null];
	}

	// 2) Buscar usuario en BDD (tabla tbl_usuarios, columna username)
	$user = obtener_usuario_por_nombre($usuario);
	if (!$user) {
		$errors['usuario'] = 'Usuario incorrecto o no existe.';
		return ['success' => false, 'errors' => $errors, 'user' => null];
	}

	// 3) Comprobar contraseña (columna 'password')
	$stored = $user['password'] ?? null;
	if ($stored === null) {
		$errors['contrasena'] = 'Configuración del usuario inválida (sin contraseña almacenada).';
		return ['success' => false, 'errors' => $errors, 'user' => null];
	}

	// Inicializamos bandera de verificación de contraseña
	$pass_ok = false;

	// Si el valor almacenado parece un hash generado por password_hash (algo !== 0),
	// usamos password_verify para comprobar la contraseña de forma segura.
	if (password_get_info($stored)['algo'] !== 0) {
		// password_verify compara la contraseña en texto plano con el hash almacenado
		// y devuelve true si coinciden.
		if (password_verify($contrasena, $stored)) {
			$pass_ok = true;
		}
	} else {
		// Fallback: si el valor almacenado no parece un hash, comparamos las cadenas
		// con hash_equals para evitar ataques por timing (aunque no es recomendado
		// almacenar contraseñas en claro).
		if (hash_equals((string)$stored, (string)$contrasena)) {
			$pass_ok = true;
		}
	}

	// Si la contraseña no coincide, añadimos un error y devolvemos fallo.
	if (!$pass_ok) {
		$errors['contrasena'] = 'Contraseña incorrecta.';
		return ['success' => false, 'errors' => $errors, 'user' => null];
	}

	// Eliminamos el campo password por seguridad antes de devolver los datos del usuario.
	unset($user['password']);
	return ['success' => true, 'errors' => [], 'user' => $user];
}

/*
Ejemplo de uso (comentado). Ajusta DSN/usuario/contraseña/BBDD:

try {
	$pdo = new PDO('mysql:host=localhost;dbname=tu_bbdd;charset=utf8mb4', 'root', '');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$result = validar_login($pdo, $_POST['usuario'] ?? '', $_POST['contrasena'] ?? '');
	if ($result['success']) {
		// login correcto: $result['user']
	} else {
		// manejar $result['errors']
	}
} catch (Exception $e) {
	// manejar error de conexión
}
*/