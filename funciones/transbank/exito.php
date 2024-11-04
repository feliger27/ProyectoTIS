<?php
// exito.php

include('../../conexion.php'); // Conexión a la base de datos
require_once('config_transbank.php'); // Configuración de Transbank

use Transbank\Webpay\WebpayPlus\Transaction;

session_start();

// Obtener el token desde la URL
$token = $_GET['token_ws'] ?? null;

if (!$token) {
    header("Location: rechazo.php");
    exit();
}

$transaction = new Transaction();

try {
    // Confirmar la transacción con el token recibido
    $response = $transaction->commit($token);

    // Verificar el estado de la transacción
    if ($response->getStatus() === 'AUTHORIZED') {
        $user_id = $_SESSION['user_id'];
        $monto = $response->getAmount(); // Asegúrate de recibir correctamente el monto
        $id_promocion = $_SESSION['id_promocion'] ?? null; // Suponiendo que guardes la promoción en la sesión
        $id_direccion = $_SESSION['id_direccion'] ?? null; // Suponiendo que guardes la dirección en la sesión
        $id_metodo_pago = $_SESSION['id_metodo_pago'] ?? null; // Suponiendo que guardes el método de pago en la sesión

        // Crear el pedido en la base de datos
        $query = "INSERT INTO pedido (id_usuario, id_promocion, total, estado_pedido, id_direccion, id_metodo_pago, fecha_pedido, monto) 
                  VALUES (?, ?, ?, 'en preparación', ?, ?, NOW(), ?)";
        $stmt = $conexion->prepare($query);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }
        $stmt->bind_param("iiiiid", $user_id, $id_promocion, $monto, $id_direccion, $id_metodo_pago, $monto);
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar el pedido: " . $stmt->error);
        }
        $stmt->close();

        // Vaciar el carrito de la sesión
        unset($_SESSION['carrito']);
        $mensaje = "Pago confirmado y pedido procesado con éxito.";
    } else {
        throw new Exception("La transacción no fue autorizada. Estado: " . $response->getStatus());
    }
} catch (Exception $e) {
    error_log("Error procesando la transacción: " . $e->getMessage());
    header("Location: rechazo.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../../login/fondo2.jpg') no-repeat center center fixed; 
            background-size: cover;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .card-title {
            color: #3c763d;
            font-family: 'Arial', sans-serif;
            font-size: 1.5rem;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.7);
        }
        .btn-custom {
            background-color: #ff9900;
            color: white;
            font-family: 'Arial', sans-serif;
            font-size: 1.2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card text-center shadow-lg">
        <div class="card-body">
            <h4 class="card-title"><?= $mensaje ?></h4>
            <a href="../../index/index-menu.php" class="btn btn-custom btn-lg mt-3">Volver al Menú</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
