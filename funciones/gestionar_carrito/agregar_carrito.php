<?php
session_start();

// Inicializar el carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Obtener datos del producto desde la solicitud POST
$idProducto = $_POST['idProducto'] ?? null;
$categoria = $_POST['categoria'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$precio = $_POST['precio'] ?? null;
$imagen = $_POST['imagen'] ?? null; // Asegúrate de que la imagen esté incluida en el formulario

if ($idProducto && $categoria && $nombre && $precio && $imagen) {
    // Verificar si el producto ya existe en el carrito
    if (!isset($_SESSION['carrito'][$categoria][$idProducto])) {
        $_SESSION['carrito'][$categoria][$idProducto] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => 1,
            'imagen' => $imagen, // Agregar el campo de la imagen
        ];
    } else {
        // Incrementar la cantidad si ya existe
        $_SESSION['carrito'][$categoria][$idProducto]['cantidad']++;
    }
}

// Contar los productos totales en el carrito
$totalProductos = 0;
foreach ($_SESSION['carrito'] as $productos) {
    foreach ($productos as $producto) {
        $totalProductos += $producto['cantidad'];
    }
}

// Retornar la cantidad total de productos como respuesta JSON
echo json_encode(['totalProductos' => $totalProductos]);
?>