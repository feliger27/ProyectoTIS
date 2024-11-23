<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['producto_id'], $data['tipo_producto'], $data['cantidad'])) {
    $productoId = $data['producto_id'];
    $tipoProducto = $data['tipo_producto'];
    $cantidad = (int) $data['cantidad'];

    // Validar y actualizar la cantidad en el carrito
    if (isset($_SESSION['carrito'][$tipoProducto][$productoId])) {
        $_SESSION['carrito'][$tipoProducto][$productoId]['cantidad'] = $cantidad;

        $precioUnitario = $_SESSION['carrito'][$tipoProducto][$productoId]['precio'];
        $subtotal = $precioUnitario * $cantidad;

        // Calcular el total actualizado del carrito
        $total = 0;
        foreach ($_SESSION['carrito'] as $productos) {
            foreach ($productos as $producto) {
                $total += $producto['precio'] * $producto['cantidad'];
            }
        }

        echo json_encode(['success' => true, 'subtotal' => $subtotal, 'total' => $total]);
        exit();
    }
}

echo json_encode(['success' => false]);
