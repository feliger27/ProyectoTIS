<?php
session_start();
include('../conexion.php');

// Obtener datos de la solicitud
$idProducto = $_POST['idProducto'] ?? null;
$categoria = $_POST['categoria'] ?? null;
$cantidad = $_POST['cantidad'] ?? 1;

if (!$idProducto || !$categoria) {
    echo json_encode(['error' => 'Datos incompletos.']);
    exit;
}

// Validar cantidad
$cantidad = max(1, (int)$cantidad);

// Cargar promociones activas
$hoy = date('Y-m-d H:i:s');
$queryPromociones = "SELECT * FROM promocion WHERE fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy'";
$resultadoPromociones = $conexion->query($queryPromociones);

$promociones = [];
if ($resultadoPromociones->num_rows > 0) {
    while ($promo = $resultadoPromociones->fetch_assoc()) {
        $promociones[] = $promo;
    }
}

// Función para calcular precio promocional
function calcularPrecioPromocional($precio, $descuento) {
    return $precio - ($precio * $descuento / 100);
}

// Función para buscar promoción activa
function obtenerPromocion($productoId, $categoria, $promociones) {
    foreach ($promociones as $promo) {
        if (($categoria === 'hamburguesa' && isset($promo['id_hamburguesa']) && $promo['id_hamburguesa'] == $productoId) ||
            ($categoria === 'bebida' && isset($promo['id_bebida']) && $promo['id_bebida'] == $productoId) ||
            ($categoria === 'acompaniamiento' && isset($promo['id_acompaniamiento']) && $promo['id_acompaniamiento'] == $productoId) ||
            ($categoria === 'postre' && isset($promo['id_postre']) && $promo['id_postre'] == $productoId) ||
            ($categoria === 'combo' && isset($promo['id_combo']) && $promo['id_combo'] == $productoId)) {
            return $promo;
        }
    }
    return null;
}

// Actualizar carrito
if (isset($_SESSION['carrito'][$categoria][$idProducto])) {
    $producto = &$_SESSION['carrito'][$categoria][$idProducto];

    // Verificar promoción
    $promocion = obtenerPromocion($idProducto, $categoria, $promociones);
    $precio = $promocion ? calcularPrecioPromocional($producto['precio'], $promocion['porcentaje_descuento']) : $producto['precio'];

    // Actualizar cantidad y subtotal
    $producto['cantidad'] = $cantidad;
    $producto['subtotal'] = $precio * $cantidad; // Asegúrate de que el subtotal también se almacene en el carrito


    // Recalcular total
    $total = 0;
    foreach ($_SESSION['carrito'] as $productos) {
        foreach ($productos as $item) {
            $itemPromocion = obtenerPromocion($item['id'], $categoria, $promociones);
            $itemPrecio = $itemPromocion ? calcularPrecioPromocional($item['precio'], $itemPromocion['porcentaje_descuento']) : $item['precio'];
            $total += $itemPrecio * $item['cantidad'];
        }        
    }

    echo json_encode(['subtotal' => $subtotal, 'total' => $total]);
} else {
    echo json_encode(['error' => 'Producto no encontrado en el carrito.']);
}
?>