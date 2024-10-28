<?php
include '../../conexion.php';
session_start();
$user_id = $_SESSION['user_id']; // ID del usuario actual

// Verificar si se envió un ID de dirección
if (isset($_GET['id_direccion'])) {
    $id_direccion = $_GET['id_direccion'];

    // Eliminar la relación entre el usuario y la dirección en `direccion_usuario`
    $query_eliminar_relacion = "DELETE FROM direccion_usuario WHERE id_usuario = ? AND id_direccion = ?";
    $stmt_eliminar_relacion = $conexion->prepare($query_eliminar_relacion);
    $stmt_eliminar_relacion->bind_param("ii", $user_id, $id_direccion);
    
    if ($stmt_eliminar_relacion->execute()) {
        // Eliminar la dirección de la tabla `direccion`
        $query_eliminar_direccion = "DELETE FROM direccion WHERE id_direccion = ?";
        $stmt_eliminar_direccion = $conexion->prepare($query_eliminar_direccion);
        $stmt_eliminar_direccion->bind_param("i", $id_direccion);

        if ($stmt_eliminar_direccion->execute()) {
            $_SESSION['mensaje_exito'] = "La dirección se ha eliminado correctamente.";
            header("Location: ../../index/index-perfil.php"); // Redirigir al perfil del usuario
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error al eliminar la dirección: " . $stmt_eliminar_direccion->error . "</div>";
        }
        $stmt_eliminar_direccion->close();
    } else {
        echo "<div class='alert alert-danger'>Error al eliminar la relación de dirección: " . $stmt_eliminar_relacion->error . "</div>";
    }
    $stmt_eliminar_relacion->close();
}

$conexion->close();
?>
