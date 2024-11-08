<?php
// confirmacion.php

include('../../conexion.php'); // Conexión a la base de datos

session_start();
if (!isset($_SESSION['user_id'])) {
    // Si no hay sesión de usuario, redirigir al login
    header("Location: ../login/login.php");
    exit();
}

// Asumimos que estos valores se establecen durante el proceso de pago o vienen como parte de la respuesta
$response = $_POST;
$total = isset($response['amount']) ? $response['amount'] : 0;
$estado_transaccion = isset($response['status']) ? $response['status'] : '';

// Estos deben ser establecidos o seleccionados previamente
$id_promocion = $_SESSION['id_promocion'] ?? null;
$id_direccion = $_SESSION['id_direccion'] ?? null;
$id_metodo_pago = $_SESSION['id_metodo_pago'] ?? null;

if ($estado_transaccion === 'APROBADO') {
    $user_id = $_SESSION['user_id'];
    $estado_pedido = 'en preparación'; // Estado predeterminado

    // Crear el pedido en la base de datos
    $query = "INSERT INTO pedido (id_usuario, id_promocion, total, estado_pedido, id_direccion, id_metodo_pago, fecha_pedido, monto) 
              VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
    $stmt = $conexion->prepare($query);
    if (!$stmt) {
        echo "Error de preparación: " . htmlspecialchars($conexion->error);
        exit();
    }

    $stmt->bind_param("iiisisd", $user_id, $id_promocion, $total, $estado_pedido, $id_direccion, $id_metodo_pago, $total);
    $resultado = $stmt->execute();
    if ($resultado) {
        unset($_SESSION['carrito']); // Vaciar el carrito
        header("Location: exito.php"); // Redirigir a la página de éxito
        exit();
    } else {
        echo "Error al insertar el pedido: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();

} else {
    header("Location: rechazo.php");
    exit();
}
?>
