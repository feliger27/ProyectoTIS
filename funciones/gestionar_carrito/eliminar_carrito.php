<?php
session_start();

// Verificar que exista el carrito y los datos necesarios
if (!isset($_SESSION['carrito'])) {
    echo json_encode(['status' => 'error', 'message' => 'El carrito está vacío.']);
    exit;
}

$idProducto = $_POST['id_producto'] ?? null; // Asegúrate de que el nombre del parámetro coincide con el enviado por JS
$categoria = $_POST['categoria'] ?? null;

if (!$idProducto || !$categoria) {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos para eliminar el producto.']);
    exit;
}

if (isset($_SESSION['carrito'][$categoria][$idProducto])) {
    unset($_SESSION['carrito'][$categoria][$idProducto]);
    if (empty($_SESSION['carrito'][$categoria])) {
        unset($_SESSION['carrito'][$categoria]);
    }
    echo json_encode(['status' => 'success', 'message' => 'Producto eliminado correctamente.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Producto no encontrado en el carrito.']);
}
exit;
?>
