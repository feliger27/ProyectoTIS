<?php
include '../../conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge los datos del formulario
    $id_pago = $_POST['id_pago'];
    $tipo_tarjeta = $_POST['tipo_tarjeta'];
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $fecha_expiracion = $_POST['fecha_expiracion'];
    $cvv = $_POST['cvv'];
    $nombre_titular = $_POST['nombre_titular'];

    // Convierte la fecha de expiración de "MM/YY" a "YYYY-MM-DD"
    list($mes, $anio) = explode('/', $fecha_expiracion);
    $anio_completo = '20' . $anio;
    $fecha_formateada = $anio_completo . '-' . $mes . '-01';

    // Actualiza el método de pago en la tabla `metodo_pago`
    $sql_update_pago = "UPDATE metodo_pago SET tipo_tarjeta = ?, numero_tarjeta = ?, fecha_expiracion = ?, cvv = ?, nombre_titular = ? WHERE id_pago = ?";
    $stmt = $conexion->prepare($sql_update_pago);
    $stmt->bind_param("sssssi", $tipo_tarjeta, $numero_tarjeta, $fecha_formateada, $cvv, $nombre_titular, $id_pago);

    if ($stmt->execute()) {
        $_SESSION['mensaje_exito'] = "Método de pago actualizado exitosamente.";
    } else {
        $_SESSION['mensaje_error'] = "Error al actualizar el método de pago.";
    }

    $stmt->close();
    $conexion->close();

    // Redirige de nuevo al perfil del usuario
    header("Location: ../../index/index-perfil.php");
    exit();
}
?>
