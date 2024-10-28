<?php
session_start();

// Recibe el ID del producto a eliminar y el tipo de producto
$producto_id = $_POST['producto_id'];
$tipo_producto = $_POST['tipo_producto']; // Tipo de producto para ubicar la categoría correcta en el carrito

// Verifica que el producto esté en el carrito
if (isset($_SESSION['carrito'][$tipo_producto][$producto_id])) {
    unset($_SESSION['carrito'][$tipo_producto][$producto_id]); // Elimina el producto del carrito
}

// Redirige de vuelta al carrito después de eliminar el producto
header('Location: ../../index/index-carrito.php');
exit();
