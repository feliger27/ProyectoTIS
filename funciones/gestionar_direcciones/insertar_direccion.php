<?php
include '../../conexion.php';
session_start();

$user_id = $_SESSION['user_id']; // ID del usuario actual

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar los datos del formulario y sanitizarlos
    $calle = mysqli_real_escape_string($conexion, $_POST['calle']);
    $numero = mysqli_real_escape_string($conexion, $_POST['numero']);
    $ciudad = mysqli_real_escape_string($conexion, $_POST['ciudad']);

    // Insertar la nueva dirección en la tabla direccion
    $query_direccion = "INSERT INTO direccion (calle, numero, ciudad) VALUES (?, ?, ?)";
    $stmt_direccion = $conexion->prepare($query_direccion);
    $stmt_direccion->bind_param("ssi", $calle, $numero, $ciudad);

    if ($stmt_direccion->execute()) {
        // Obtener el ID de la dirección recién insertada
        $direccion_id = $stmt_direccion->insert_id;

        // Insertar la relación en la tabla direccion_usuario
        $query_direccion_usuario = "INSERT INTO direccion_usuario (id_usuario, id_direccion) VALUES (?, ?)";
        $stmt_direccion_usuario = $conexion->prepare($query_direccion_usuario);
        $stmt_direccion_usuario->bind_param("ii", $user_id, $direccion_id);

        if ($stmt_direccion_usuario->execute()) {
            $_SESSION['mensaje_exito'] = "La dirección se ha añadido correctamente.";
            header("Location: ../../index/index-perfil.php"); // Redirigir de nuevo al perfil del usuario
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error al guardar la dirección del usuario: " . $stmt_direccion_usuario->error . "</div>";
        }
        $stmt_direccion_usuario->close();
    } else {
        echo "<div class='alert alert-danger'>Error al guardar la dirección: " . $stmt_direccion->error . "</div>";
    }
    $stmt_direccion->close();
}

$conexion->close();
?>
