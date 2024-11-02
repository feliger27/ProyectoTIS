<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];

    // Primero, elimina la relación en la tabla usuario_rol
    $sql_relacion = "DELETE FROM usuario_rol WHERE id_usuario = ?";
    $stmt_relacion = $conexion->prepare($sql_relacion);
    $stmt_relacion->bind_param("i", $id_usuario);
    $stmt_relacion->execute();
    $stmt_relacion->close();

    // Luego, elimina al usuario
    $sql_usuario = "DELETE FROM usuario WHERE id_usuario = ?";
    $stmt_usuario = $conexion->prepare($sql_usuario);
    $stmt_usuario->bind_param("i", $id_usuario);

    if ($stmt_usuario->execute()) {
        header("Location: listar.php?eliminado=true&id=" . $id_usuario);
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt_usuario->error . "</div>";
    }
    $stmt_usuario->close();
}
?>
