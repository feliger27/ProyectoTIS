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
    // Verificación especial para combos
    if ($tipo_producto === 'combo') {
        $stock_suficiente = true;

        // Verificar hamburguesas del combo
        $query = "SELECT ch.id_hamburguesa, ch.cantidad AS cantidad_necesaria 
                  FROM combo_hamburguesa ch 
                  WHERE ch.id_combo = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $hamburguesa_id = $row['id_hamburguesa'];
            $cantidad_necesaria = $row['cantidad_necesaria'] * $cantidad;

            // Verificar los ingredientes necesarios para la hamburguesa
            $query_ingredientes = "SELECT hi.id_ingrediente, hi.cantidad AS cantidad_por_hamburguesa, i.cantidad AS stock 
                                   FROM hamburguesa_ingrediente hi 
                                   JOIN ingrediente i ON hi.id_ingrediente = i.id_ingrediente 
                                   WHERE hi.id_hamburguesa = ?";
            $stmt_ingredientes = $conexion->prepare($query_ingredientes);
            $stmt_ingredientes->bind_param("i", $hamburguesa_id);
            $stmt_ingredientes->execute();
            $result_ingredientes = $stmt_ingredientes->get_result();

            while ($ingrediente = $result_ingredientes->fetch_assoc()) {
                $cantidad_total_necesaria = $ingrediente['cantidad_por_hamburguesa'] * $cantidad_necesaria;
                if ($ingrediente['stock'] < $cantidad_total_necesaria) {
                    $stock_suficiente = false;
                    break 2; // Rompe ambos bucles si un ingrediente está agotado
                }
            }
        }

        // Verificar stock para otros productos del combo de forma similar
        // Aquí podríamos agregar lógica para acompañamientos, bebidas, y postres si fuera necesario.

        // Si no hay stock suficiente, redirige con un mensaje de error
        if (!$stock_suficiente) {
            header('Location: ../../index/index-menu.php?error=stock_insuficiente');
            exit();
        }
    }

    // Verifica si el producto ya está en el carrito
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
