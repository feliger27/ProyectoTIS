<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que se reciban los datos necesarios
if (!isset($_POST['id_producto'], $_POST['categoria'], $_POST['cantidad'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Faltan datos necesarios para actualizar el producto.'
    ]);
    exit;
}

$idProducto = $_POST['id_producto'];
$categoria = $_POST['categoria'];
$cantidad = (int)$_POST['cantidad'];

// Validar cantidad mínima
if ($cantidad <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'La cantidad debe ser mayor a cero.'
    ]);
    exit;
}

// Verificar si el producto existe en el carrito
if (isset($_SESSION['carrito'][$categoria][$idProducto])) {
    // Actualizar la cantidad del producto
    $_SESSION['carrito'][$categoria][$idProducto]['cantidad'] = $cantidad;

    // Calcular el número total de productos en el carrito
    $numero_productos = 0;
    foreach ($_SESSION['carrito'] as $productos) {
        foreach ($productos as $producto) {
            $numero_productos += $producto['cantidad'];
        }
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'La cantidad del producto se ha actualizado exitosamente.',
        'cartCount' => $numero_productos
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'El producto no existe en el carrito.'
    ]);
}