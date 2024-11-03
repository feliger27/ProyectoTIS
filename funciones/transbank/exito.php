<?php
// exito.php

include('../../conexion.php'); // Conexión a la base de datos
include('config_transbank.php'); // Configuración de Transbank

use Transbank\Webpay\WebpayPlus\Transaction;

session_start();

// Obtener el token desde la URL
$token = $_GET['token_ws'] ?? null;

if ($token) {
    $transaction = new Transaction();

    try {
        // Confirmar la transacción con el token recibido
        $response = $transaction->commit($token);

        // Verificar el estado de la transacción
        if ($response->getStatus() === 'AUTHORIZED') {
            $user_id = $_SESSION['user_id'];

            // Crear el pedido en la base de datos
            $query = "INSERT INTO pedido (id_usuario, fecha_pedido) VALUES (?, NOW())";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();

            // Vaciar el carrito de la sesión
            unset($_SESSION['carrito']);

            $mensaje = "Pago confirmado y pedido procesado con éxito.";
        } else {
            header("Location: rechazo.php");
            exit();
        }
    } catch (Exception $e) {
        header("Location: rechazo.php");
        exit();
    }
} else {
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
            background-color: rgba(255, 255, 255, 0.9); /* Color de fondo con opacidad */
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Sombra */
        }
        .card-title {
            color: #3c763d; /* Verde para el texto de éxito */
            font-family: 'Arial', sans-serif; /* Cambiar la fuente */
            font-size: 1.5rem; /* Tamaño de fuente */
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.7); /* Sombra del texto */
        }
        .btn-custom {
            background-color: #ff9900; /* Color del botón */
            color: white; /* Color del texto del botón */
            font-family: 'Arial', sans-serif; /* Cambiar la fuente del botón */
            font-size: 1.2rem; /* Tamaño del texto del botón */
            border-radius: 10px; /* Bordes del botón */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Sombra del botón */
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
