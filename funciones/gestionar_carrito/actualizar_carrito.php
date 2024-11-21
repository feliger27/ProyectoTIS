<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProducto = $_POST['id_producto'] ?? null;
    $categoria = $_POST['categoria'] ?? null;
    $cantidad = $_POST['cantidad'] ?? 1;

    // Verificar si el producto existe en el carrito
    if ($idProducto && $categoria && isset($_SESSION['carrito'][$categoria][$idProducto])) {
        if ($cantidad > 0) {
            // Actualizar la cantidad del producto
            $_SESSION['carrito'][$categoria][$idProducto]['cantidad'] = $cantidad;
        } else {
            // Si la cantidad es 0, eliminar el producto
            unset($_SESSION['carrito'][$categoria][$idProducto]);
        }
    }
}

// Redirigir de vuelta al carrito
header('Location: ../../index/index-carrito.php');
exit;
?>