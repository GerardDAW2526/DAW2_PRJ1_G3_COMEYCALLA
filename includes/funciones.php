<?php
// includes/funciones.php
require_once __DIR__ . '/../conexion/conexion.php';

/**
 * Obtener todas las salas
 */
function getSalas(PDO $conn) {
    $sql = "SELECT * FROM tbl_salas ORDER BY nombre_sala";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtener todas las mesas de una sala
 */
function getMesasPorSala(PDO $conn, int $id_sala) {
    $sql = "SELECT * FROM tbl_mesas WHERE id_sala = :id_sala ORDER BY id_mesa";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Ocupar una mesa (usa transacción)
 */
function ocuparMesa(PDO $conn, int $id_mesa, int $id_usuario): bool {
    try {
        $conn->beginTransaction();

        // Verificar que la mesa esté libre
        $stmt = $conn->prepare("SELECT estado FROM tbl_mesas WHERE id_mesa = :id_mesa FOR UPDATE");
        $stmt->execute([':id_mesa' => $id_mesa]);
        $estado = $stmt->fetchColumn();

        if ($estado !== 'libre') {
            $conn->rollBack();
            return false; // mesa ya ocupada
        }

        // Cambiar estado de mesa
        $stmt = $conn->prepare("UPDATE tbl_mesas SET estado = 'ocupada' WHERE id_mesa = :id_mesa");
        $stmt->execute([':id_mesa' => $id_mesa]);

        // Registrar ocupación
        $stmt = $conn->prepare("
            INSERT INTO tbl_ocupaciones (id_mesa, id_usuario, fecha_ocupacion)
            VALUES (:id_mesa, :id_usuario, NOW())
        ");
        $stmt->execute([
            ':id_mesa' => $id_mesa,
            ':id_usuario' => $id_usuario
        ]);

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error al ocupar mesa: " . $e->getMessage());
        return false;
    }
}

/**
 * Liberar una mesa (usa transacción)
 */
function liberarMesa(PDO $conn, int $id_mesa, int $id_usuario): bool {
    try {
        $conn->beginTransaction();

        // Verificar ocupación activa
        $stmt = $conn->prepare("
            SELECT id_ocupacion FROM tbl_ocupaciones
            WHERE id_mesa = :id_mesa AND fecha_liberacion IS NULL
            ORDER BY fecha_ocupacion DESC LIMIT 1
        ");
        $stmt->execute([':id_mesa' => $id_mesa]);
        $id_ocupacion = $stmt->fetchColumn();

        if (!$id_ocupacion) {
            $conn->rollBack();
            return false; // mesa ya libre
        }

        // Actualizar fecha de liberación
        $stmt = $conn->prepare("
            UPDATE tbl_ocupaciones
            SET fecha_liberacion = NOW()
            WHERE id_ocupacion = :id_ocupacion
        ");
        $stmt->execute([':id_ocupacion' => $id_ocupacion]);

        // Cambiar estado de mesa
        $stmt = $conn->prepare("UPDATE tbl_mesas SET estado = 'libre' WHERE id_mesa = :id_mesa");
        $stmt->execute([':id_mesa' => $id_mesa]);

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error al liberar mesa: " . $e->getMessage());
        return false;
    }
}

/**
 * Obtener histórico de ocupaciones con filtros opcionales
 */
function getOcupaciones(PDO $conn, ?int $id_sala = null, ?int $id_mesa = null): array {
    $sql = "
        SELECT o.*, m.id_sala, s.nombre_sala, u.username, u.nombre_completo
        FROM tbl_ocupaciones o
        JOIN tbl_mesas m ON o.id_mesa = m.id_mesa
        JOIN tbl_salas s ON m.id_sala = s.id_sala
        JOIN tbl_usuarios u ON o.id_usuario = u.id_usuario
        WHERE 1=1
    ";

    $params = [];

    if ($id_sala) {
        $sql .= " AND s.id_sala = :id_sala";
        $params[':id_sala'] = $id_sala;
    }
    if ($id_mesa) {
        $sql .= " AND m.id_mesa = :id_mesa";
        $params[':id_mesa'] = $id_mesa;
    }

    $sql .= " ORDER BY o.fecha_ocupacion DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
