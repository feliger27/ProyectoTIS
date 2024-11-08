<?php
session_start();

// Obtener el ID y tipo de producto desde la URL
$producto_id = $_GET['producto_id'] ?? null;
$tipo_producto = $_GET['tipo_producto'] ?? null;

if ($producto_id && $tipo_producto && isset($_SESSION['carrito'][$tipo_producto][$producto_id])) {
    // Elimina el producto específico del carrito
    unset($_SESSION['carrito'][$tipo_producto][$producto_id]);

    // Si el tipo de producto ya no tiene elementos, elimina el tipo
    if (empty($_SESSION['carrito'][$tipo_producto])) {
        unset($_SESSION['carrito'][$tipo_producto]);
    }

    header("Location: ../../index/index-carrito.php?success=producto_eliminado");
} else {
    header("Location: ../../index/index-carrito.php?error=producto_no_encontrado");
}
exit();
