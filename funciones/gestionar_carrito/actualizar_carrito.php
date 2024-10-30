<?php
session_start();

// Recibe los datos del formulario
$producto_id = $_POST['producto_id'];
$cantidad = $_POST['cantidad'];
$tipo_producto = $_POST['tipo_producto']; // Tipo de producto para ubicar la categoría correcta en el carrito

// Verifica que el producto esté en el carrito
if (isset($_SESSION['carrito'][$tipo_producto][$producto_id])) {
    // Si la cantidad es mayor a cero, actualiza; si es cero, elimina el producto del carrito
    if ($cantidad > 0) {
        $_SESSION['carrito'][$tipo_producto][$producto_id]['cantidad'] = $cantidad;
    } else {
        unset($_SESSION['carrito'][$tipo_producto][$producto_id]);
    }
}

// Redirige de vuelta al carrito después de actualizar
header('Location: ../../index/index-carrito.php');
exit();
