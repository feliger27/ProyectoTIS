<?php
include '../../conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge los datos del formulario
    $tipo_tarjeta = $_POST['tipo_tarjeta'];
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $fecha_expiracion = $_POST['fecha_expiracion'];
    $cvv = $_POST['cvv'];
    $user_id = $_SESSION['user_id'];
    $nombre_titular = $_POST['nombre_titular'];

    // Convierte la fecha de expiración de "MM/YY" a "YYYY-MM-DD"
    list($mes, $anio) = explode('/', $fecha_expiracion);
    $anio_completo = '20' . $anio;
    $fecha_formateada = $anio_completo . '-' . $mes . '-01';

    // Inserta el método de pago en la tabla `metodo_pago`
    $sql_metodo_pago = "INSERT INTO metodo_pago (tipo_tarjeta, numero_tarjeta, fecha_expiracion, cvv, nombre_titular) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql_metodo_pago);
    $stmt->bind_param("sssss", $tipo_tarjeta, $numero_tarjeta, $fecha_formateada, $cvv, $nombre_titular);

    if ($stmt->execute()) {
        $id_pago = $stmt->insert_id;

        // Relaciona el método de pago con el usuario en la tabla `usuario_metodo_pago`
        $sql_usuario_metodo_pago = "INSERT INTO usuario_metodo_pago (id_usuario, id_pago) VALUES (?, ?)";
        $stmt_relacion = $conexion->prepare($sql_usuario_metodo_pago);
        $stmt_relacion->bind_param("ii", $user_id, $id_pago);

        if ($stmt_relacion->execute()) {
            $_SESSION['mensaje_exito'] = "Método de pago agregado exitosamente.";
        } else {
            $_SESSION['mensaje_error'] = "Error al asociar el método de pago con el usuario.";
        }
        $stmt_relacion->close();
    } else {
        $_SESSION['mensaje_error'] = "Error al agregar el método de pago.";
    }

    $stmt->close();
    $conexion->close();

    // Redirige de nuevo al perfil del usuario
    header("Location: ../../index/index-perfil.php");
    exit();
}
?>

