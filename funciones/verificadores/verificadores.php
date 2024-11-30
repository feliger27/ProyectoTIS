<?php
include '../conexion.php'; // asegúrate de tener la conexión disponible

// Función para verificar uno o varios permisos para el usuario
function verificarPermisos($permisos_requeridos) {
    global $conexion;

    // Verifica si el usuario está logueado
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $user_id = $_SESSION['user_id'];
    $placeholders = implode(',', array_fill(0, count($permisos_requeridos), '?'));
    $query = "SELECT COUNT(*) as permiso_count FROM usuario_rol ur
              JOIN rol_permiso rp ON ur.id_rol = rp.id_rol
              JOIN permiso p ON rp.id_permiso = p.id_permiso
              WHERE ur.id_usuario = ? AND p.nombre_permiso IN ($placeholders)";
    
    $stmt = $conexion->prepare($query);
    $params = array_merge([$user_id], $permisos_requeridos);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['permiso_count'] > 0;
}