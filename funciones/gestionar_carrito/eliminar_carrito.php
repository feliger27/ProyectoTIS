<?php
session_start();

// Verificar si el carrito existe
if (!isset($_SESSION['carrito'])) {
    echo json_encode(['error' => 'El carrito está vacío.']);
    exit;
}

// Obtener datos del producto desde POST
$idProducto = $_POST['idProducto'] ?? null;
$categoria = $_POST['categoria'] ?? null;

if (!$idProducto || !$categoria) {
    echo json_encode(['error' => 'Datos inválidos para eliminar el producto.']);
    exit;
}

// Eliminar el producto del carrito
if (isset($_SESSION['carrito'][$categoria][$idProducto])) {
    unset($_SESSION['carrito'][$categoria][$idProducto]);

    // Eliminar la categoría si está vacía
    if (empty($_SESSION['carrito'][$categoria])) {
        unset($_SESSION['carrito'][$categoria]);
    }
} else {
    echo json_encode(['error' => 'Producto no encontrado en el carrito.']);
    exit;
}

// Calcular el nuevo total
$total = 0;
foreach ($_SESSION['carrito'] as $productos) {
    foreach ($productos as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
}

// Retornar el nuevo total
echo json_encode(['total' => $total]);
?>
