<?php
include '../../conexion.php';  // Asegúrate de que la ruta sea correcta

// Verificar si se ha proporcionado un ID
if (!isset($_GET['id'])) {
    header("Location: listar_permisos.php");
    exit();
}

$id_permiso = intval($_GET['id']);

// Eliminar las relaciones en la tabla intermedia `rol_permiso`
$delete_relacion = "DELETE FROM rol_permiso WHERE id_permiso = ?";
$stmt_relacion = $conexion->prepare($delete_relacion);
$stmt_relacion->bind_param("i", $id_permiso);
$stmt_relacion->execute();
$stmt_relacion->close();

// Eliminar el permiso
$delete_query = "DELETE FROM permiso WHERE id_permiso = ?";
$stmt = $conexion->prepare($delete_query);
$stmt->bind_param("i", $id_permiso);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: listar_permisos.php?mensaje=Permiso eliminado correctamente");
} else {
    header("Location: listar_permisos.php?mensaje=Error al eliminar el permiso");
}

$stmt->close();
$conexion->close();
?>
