<?php
// confirmacion.php

include('../../conexion.php'); // Conexión a la base de datos

// Obtener la respuesta de Transbank
$response = $_POST;

// Verificar si el valor 'status' existe y su valor
echo "<pre>";
print_r($response); // Imprime todo el contenido de $_POST
echo "</pre>";
exit();

// Verificar si el pago fue aprobado o rechazado
if (isset($response['status']) && $response['status'] === 'APROBADO') {
    session_start();
    $user_id = $_SESSION['user_id'];

    // Crear el pedido en la base de datos con estado "en preparación"
    $query = "INSERT INTO pedido (id_usuario, fecha_pedido, estado) VALUES (?, NOW(), 'en preparación')";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Vaciar el carrito de la sesión después de procesar el pedido
    unset($_SESSION['carrito']);

    // Redirigir a la página de éxito
    header("Location: exito.php");
    exit();

} else {
    // Redirigir a la página de rechazo si el pago no fue aprobado
    header("Location: rechazo.php");
    exit();
}
?>
