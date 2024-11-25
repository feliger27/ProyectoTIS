<?php
session_start();
include('../../conexion.php');

// Obtener las promociones activas
$hoy = date('Y-m-d H:i:s'); // Fecha y hora actual
$queryPromociones = "SELECT * FROM promocion WHERE fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy'";
$resultadoPromociones = $conexion->query($queryPromociones);

$promociones = [];
if ($resultadoPromociones->num_rows > 0) {
    while ($promo = $resultadoPromociones->fetch_assoc()) {
        $promociones[] = $promo;
    }
}

// Función para calcular el precio promocional
function calcularPrecioPromocional($precio, $descuento)
{
    return $precio - ($precio * $descuento / 100);
}

// Función para buscar una promoción activa para un producto
function obtenerPromocion($productoId, $categoria)
{
    global $promociones; // Asegúrate de que $promociones está disponible dentro de esta función

    foreach ($promociones as $promo) {
        if (
            ($categoria === 'hamburguesa' && isset($promo['id_hamburguesa']) && $promo['id_hamburguesa'] == $productoId) ||
            ($categoria === 'bebida' && isset($promo['id_bebida']) && $promo['id_bebida'] == $productoId) ||
            ($categoria === 'acompaniamiento' && isset($promo['id_acompaniamiento']) && $promo['id_acompaniamiento'] == $productoId) ||
            ($categoria === 'postre' && isset($promo['id_postre']) && $promo['id_postre'] == $productoId) ||
            ($categoria === 'combo' && isset($promo['id_combo']) && $promo['id_combo'] == $productoId)
        ) {
            return $promo;
        }
    }
    return null;
}

if (isset($_POST['idProducto'], $_POST['categoria'], $_POST['cantidad'])) {
    $idProducto = $_POST['idProducto'];
    $categoria = $_POST['categoria'];
    $cantidad = $_POST['cantidad'];

    // Verificar que la cantidad sea válida
    if ($cantidad < 1) {
        echo json_encode(['error' => 'La cantidad debe ser al menos 1']);
        exit;
    }

    // Verificar si el producto está en el carrito
    if (isset($_SESSION['carrito'][$categoria][$idProducto])) {
        // Actualizar la cantidad del producto
        $_SESSION['carrito'][$categoria][$idProducto]['cantidad'] = $cantidad;

        // Obtener el producto del carrito
        $producto = $_SESSION['carrito'][$categoria][$idProducto];
        $precioUnitario = $producto['precio'];

        // Verificar si tiene promoción
        $promocion = obtenerPromocion($idProducto, $categoria);
        if ($promocion) {
            $precioUnitario = calcularPrecioPromocional($producto['precio'], $promocion['porcentaje_descuento']);
        }

        // Calcular el subtotal
        $subtotal = $precioUnitario * $cantidad;

        // Calcular el total del carrito
        $total = calcularTotalCarrito();

        // Devolver los datos actualizados en JSON
        echo json_encode([
            'subtotal' => '$' . number_format($subtotal, 0, ',', '.'),
            'total' => '$' . number_format($total, 0, ',', '.')
        ]);
    } else {
        echo json_encode(['error' => 'Producto no encontrado en el carrito']);
    }
} else {
    echo json_encode(['error' => 'Datos insuficientes']);
}



// Función para calcular el total del carrito
function calcularTotalCarrito()
{
    $total = 0;
    foreach ($_SESSION['carrito'] as $categoria => $productos) {
        foreach ($productos as $producto) {
            $precioUnitario = $producto['precio']; // Precio base

            // Obtener la promoción activa para el producto
            $promocion = obtenerPromocion($producto['idProducto'], $categoria);
            // Si hay promoción, aplicar descuento
            if ($promocion) {
                $precioUnitario = calcularPrecioPromocional($producto['precio'], $promocion['porcentaje_descuento']);
            }

            $cantidad = $producto['cantidad'];

            // Acumular en el total
            $total += $precioUnitario * $cantidad;
        }
    }

    return $total;
}

?>