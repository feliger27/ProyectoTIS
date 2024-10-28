<?php
// verificadores.php

include '../conexion.php'; // asegúrate de tener la conexión disponible

function tienePermisosMantenedores($permisosUsuario) {
    $permisosMantenedores = [
        'ver_usuarios', 'crear_usuario', 'editar_usuario', 'eliminar_usuario',
        'ver_roles', 'crear_rol', 'editar_rol', 'eliminar_rol',
        'ver_productos', 'crear_producto', 'editar_producto', 'eliminar_producto',
        'ver_reportes', 'generar_reporte_ventas'
    ];

    foreach ($permisosMantenedores as $permiso) {
        if (in_array($permiso, $permisosUsuario)) {
            return true;
        }
    }
    return false;
}

// Función para verificar un permiso específico para el usuario
function verificarPermiso($permiso_nombre) {
    global $conexion;

    // Verifica si el usuario está logueado
    if (!isset($_SESSION['user_id'])) {
        return false; // Usuario no logueado, sin permiso
    }

    // Obtén el ID del usuario desde la sesión
    $user_id = $_SESSION['user_id'];

    $query = "SELECT COUNT(*) as permiso_count FROM usuario_rol ur
              JOIN rol_permiso rp ON ur.id_rol = rp.id_rol
              JOIN permiso p ON rp.id_permiso = p.id_permiso
              WHERE ur.id_usuario = ? AND p.nombre = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("is", $user_id, $permiso_nombre);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['permiso_count'] > 0;
}
