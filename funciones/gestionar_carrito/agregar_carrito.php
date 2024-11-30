<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si se recibieron los datos necesarios
$inputData = json_decode(file_get_contents('php://input'), true);
if (!isset($inputData['idProducto'], $inputData['categoria'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos incompletos. No se pudo agregar el producto al carrito.'
    ]);
    exit;
}

$idProducto = (int)$inputData['idProducto'];
$categoria = $inputData['categoria'];

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar el producto al carrito
if (!isset($_SESSION['carrito'][$categoria][$idProducto])) {
    // Si no existe en el carrito, inicializar con cantidad 1
    $_SESSION['carrito'][$categoria][$idProducto] = [
        'id' => $idProducto,
        'cantidad' => 1
    ];
} else {
    // Si ya existe, incrementar la cantidad
    $_SESSION['carrito'][$categoria][$idProducto]['cantidad']++;
}

// Calcular el número total de productos en el carrito
$numeroProductos = 0;
foreach ($_SESSION['carrito'] as $productos) {
    foreach ($productos as $producto) {
        $numeroProductos += $producto['cantidad'];
    }
}

// Responder con el conteo actualizado del carrito
header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'message' => 'Producto agregado al carrito exitosamente.',
    'cartCount' => $numeroProductos
]);
exit;