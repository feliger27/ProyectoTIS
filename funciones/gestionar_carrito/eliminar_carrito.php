<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que se reciban los datos necesarios
if (!isset($_POST['id_producto'], $_POST['categoria'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Faltan datos necesarios para eliminar el producto.'
    ]);
    exit;
}

$idProducto = $_POST['id_producto'];
$categoria = $_POST['categoria'];

// Verificar si el carrito y la categoría existen
if (isset($_SESSION['carrito'][$categoria][$idProducto])) {
    // Eliminar el producto del carrito
    unset($_SESSION['carrito'][$categoria][$idProducto]);

    // Si la categoría queda vacía, eliminarla también
    if (empty($_SESSION['carrito'][$categoria])) {
        unset($_SESSION['carrito'][$categoria]);
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Producto eliminado del carrito exitosamente.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'El producto no existe en el carrito.'
    ]);
}