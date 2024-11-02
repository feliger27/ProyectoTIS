<?php
// Archivo: eliminar_rol.php

include '../../conexion.php'; 
session_start();

// Verificar si se recibió el ID del rol a eliminar
if (!isset($_GET['id_rol'])) {
    header("Location: listar_roles.php");
    exit();
}

$id_rol = $_GET['id_rol'];

// Eliminar permisos asociados al rol en la tabla `rol_permiso`
$query_delete_permissions = "DELETE FROM rol_permiso WHERE id_rol = ?";
$stmt_delete_permissions = $conexion->prepare($query_delete_permissions);
$stmt_delete_permissions->bind_param("i", $id_rol);
$stmt_delete_permissions->execute();
$stmt_delete_permissions->close();

// Eliminar el rol de la tabla `rol`
$query_delete_role = "DELETE FROM rol WHERE id_rol = ?";
$stmt_delete_role = $conexion->prepare($query_delete_role);
$stmt_delete_role->bind_param("i", $id_rol);

if ($stmt_delete_role->execute()) {
    $_SESSION['mensaje_exito'] = "Rol eliminado correctamente.";
} else {
    $_SESSION['mensaje_error'] = "Error al eliminar el rol: " . $stmt_delete_role->error;
}

$stmt_delete_role->close();
$conexion->close();

// Redirigir de regreso a listar_roles.php
header("Location: listar_roles.php");
exit();
?>
