<?php
session_start();
include_once '../../conexion.php'; // Incluye la conexión a la base de datos

// Recibe los datos del formulario
$producto_id = $_POST['producto_id'];
$cantidad = $_POST['cantidad'];
$tipo_producto = $_POST['tipo_producto']; // Nuevo campo para el tipo de producto

// Determina la tabla según el tipo de producto
$tabla = '';
switch ($tipo_producto) {
    case 'combo':
        $tabla = 'combo';
        break;
    case 'hamburguesa':
        $tabla = 'hamburguesa';
        break;
    case 'acompaniamiento':
        $tabla = 'acompaniamiento';
        break;
    case 'bebida':
        $tabla = 'bebida';
        break;
    case 'postre':
        $tabla = 'postre';
        break;
    default:
        die("Tipo de producto no válido.");
}

// Consulta para obtener el producto desde la base de datos
$query = "SELECT * FROM $tabla WHERE id_" . $tipo_producto . " = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if ($producto) {
    // Verifica si el producto ya está en el carrito
    if (isset($_SESSION['carrito'][$tipo_producto][$producto_id])) {
        $_SESSION['carrito'][$tipo_producto][$producto_id]['cantidad'] += $cantidad;
    } else {
        // Agrega el producto al carrito
        $_SESSION['carrito'][$tipo_producto][$producto_id] = [
            'id' => $producto_id,
            'nombre' => $producto['nombre_' . $tipo_producto],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad
        ];
    }
}

// Redirige al menú o muestra un mensaje de éxito
header('Location: ../../index/index-menu.php');
exit();
