<?php
include '../../conexion.php';
session_start();
$user_id = $_SESSION['user_id']; // ID del usuario actual

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_direccion'])) {
    $id_direccion = $_POST['id_direccion'];

    // Capturar y sanitizar los datos del formulario
    $calle = mysqli_real_escape_string($conexion, $_POST['calle']);
    $numero = mysqli_real_escape_string($conexion, $_POST['numero']);
    $ciudad = mysqli_real_escape_string($conexion, $_POST['ciudad']);
    $depto_oficina_piso = mysqli_real_escape_string($conexion, $_POST['depto_oficina_piso']);

    // Actualizar la dirección en la tabla `direccion`
    $query_actualizar_direccion = "UPDATE direccion SET calle = ?, numero = ?, ciudad = ?, depto_oficina_piso = ? WHERE id_direccion = ?";
    $stmt_actualizar_direccion = $conexion->prepare($query_actualizar_direccion);
    $stmt_actualizar_direccion->bind_param("sisii", $calle, $numero, $ciudad, $depto_oficina_piso , $id_direccion);

    if ($stmt_actualizar_direccion->execute()) {
        $_SESSION['mensaje_exito'] = "La dirección se ha actualizado correctamente.";
        header("Location: ../../index/index-perfil.php"); // Redirigir al perfil del usuario
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar la dirección: " . $stmt_actualizar_direccion->error . "</div>";
    }

    $stmt_actualizar_direccion->close();
}

$conexion->close();
?>
