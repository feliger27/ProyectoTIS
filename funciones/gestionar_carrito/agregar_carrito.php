<?php
session_start();
include '../../conexion.php';

$producto_id = $_POST['producto_id'] ?? null;
$cantidad = $_POST['cantidad'] ?? 1;
$tipo_producto = $_POST['tipo_producto'] ?? '';

if (!$producto_id || !$tipo_producto) {
    header('Location: ../../index/index-menu.php?error=producto_no_valido');
    exit();
}

$nombre_campo_id = "id_$tipo_producto";
$tabla = $tipo_producto;

// Consulta para obtener el producto desde la base de datos
$query = "SELECT * FROM $tabla WHERE $nombre_campo_id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if ($producto) {
    // Verifica si el producto ya está en el carrito y actualiza la cantidad
    if (!isset($_SESSION['carrito'][$tipo_producto])) {
        $_SESSION['carrito'][$tipo_producto] = [];
    }

    if (isset($_SESSION['carrito'][$tipo_producto][$producto_id])) {
        $_SESSION['carrito'][$tipo_producto][$producto_id]['cantidad'] += $cantidad;
    } else {
        // Agrega el producto al carrito
        $_SESSION['carrito'][$tipo_producto][$producto_id] = [
            'nombre' => $producto['nombre_' . $tipo_producto],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad
        ];
    }

    header('Location: ../../index/index-menu.php?success=producto_agregado');
    exit();
} else {
    header('Location: ../../index/index-menu.php?error=producto_no_encontrado');
    exit();
}
?>
