<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProducto = $_POST['id_producto'] ?? null;
    $categoria = $_POST['categoria'] ?? null;

    // Verificar si el producto existe en el carrito
    if ($idProducto && $categoria && isset($_SESSION['carrito'][$categoria][$idProducto])) {
        // Eliminar el producto del carrito
        unset($_SESSION['carrito'][$categoria][$idProducto]);
    }
}

// Redirigir de vuelta al carrito
header('Location: ../../index/index-carrito.php');
exit;
?>