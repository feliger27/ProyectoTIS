<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../conexion.php';
require_once '../../vendor/autoload.php';

use Transbank\Webpay\WebpayPlus\Transaction;
use Transbank\Webpay\Options;

// Validar los datos necesarios
if (!isset($_POST['id_usuario'], $_POST['id_direccion'], $_POST['total_compra'], $_POST['puntos_usados'], $_POST['metodo_pago'], $_SESSION['carrito'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Faltan datos necesarios para procesar el pago.'
    ]);
    exit;
}

$idUsuario = (int)$_POST['id_usuario'];
$idDireccion = (int)$_POST['id_direccion'];
$totalCompra = (float)$_POST['total_compra'];
$puntosUsados = (int)$_POST['puntos_usados'];
$metodoPago = $_POST['metodo_pago']; // Campo para identificar el método de pago
$carrito = $_SESSION['carrito'];

// Verificar el método de pago
if ($metodoPago === 'transbank') {
    // Crear instancia de Transbank
    $transaction = new Transaction();

    $buyOrder = uniqid(); // Generar un identificador único para la orden
    $sessionId = session_id(); // ID de sesión actual
    $returnUrl = 'http://localhost/xampp/ProyectoTIS/funciones/compra/confirmar_pago_transbank.php'; // URL de retorno

    try {
        // Crear la transacción
        $response = $transaction->create($buyOrder, $sessionId, $totalCompra, $returnUrl);

        // Obtener el token y la URL de redirección
        $token = $response->getToken();
        $url = $response->getUrl();

        // Guardar temporalmente datos relevantes en la sesión
        $_SESSION['transbank'] = [
            'id_usuario' => $idUsuario,
            'id_direccion' => $idDireccion,
            'total_compra' => $totalCompra,
            'puntos_usados' => $puntosUsados,
            'carrito' => $carrito,
            'buyOrder' => $buyOrder
        ];

        // Redirigir a Transbank
        header("Location: {$url}?token_ws={$token}");
        exit;
    } catch (Exception $e) {
        error_log("Error al iniciar la transacción con Transbank: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al iniciar la transacción con Transbank.',
            'details' => $e->getMessage()
        ]);
        exit;
    }
}

try {
    // **Flujo actual para pagos en efectivo**
    $conexion->begin_transaction();

    // Paso 1: Registrar el pedido
    $queryPedido = "
        INSERT INTO pedido (id_usuario, estado_pedido, id_direccion, fecha_pedido, monto_total) 
        VALUES (?, ?, ?, NOW(), ?)
    ";

    $stmtPedido = $conexion->prepare($queryPedido);
    if (!$stmtPedido) {
        throw new Exception("Error al preparar la consulta del pedido: " . $conexion->error);
    }

    $estadoPedido = 'en_preparacion';

    if (!$stmtPedido->bind_param('isis', $idUsuario, $estadoPedido, $idDireccion, $totalCompra)) {
        throw new Exception("Error al vincular parámetros: " . $stmtPedido->error);
    }

    if (!$stmtPedido->execute()) {
        throw new Exception("Error al ejecutar la consulta del pedido: " . $stmtPedido->error);
    }

    $idPedido = $stmtPedido->insert_id;
    if (!$idPedido) {
        throw new Exception("No se pudo obtener el ID del pedido.");
    }

    $stmtPedido->close();

    // Paso 2: Procesar productos del carrito
    foreach ($carrito as $categoria => $productos) {
        foreach ($productos as $productoId => $producto) {
            $cantidad = $producto['cantidad'];

            // Obtener precio y detalles del producto
            $queryProducto = "SELECT precio FROM $categoria WHERE id_$categoria = $productoId";
            $resultadoProducto = $conexion->query($queryProducto);

            if ($resultadoProducto->num_rows > 0) {
                $detallesProducto = $resultadoProducto->fetch_assoc();
                $precioUnitario = (float)$detallesProducto['precio'];

                $queryPromocion = "
                    SELECT id_promocion, porcentaje_descuento 
                    FROM promocion 
                    WHERE id_$categoria = $productoId AND NOW() BETWEEN fecha_inicio AND fecha_fin
                ";
                $resultadoPromocion = $conexion->query($queryPromocion);
                $promocion = $resultadoPromocion->fetch_assoc();

                $precioProcesado = $promocion
                    ? $precioUnitario - ($precioUnitario * $promocion['porcentaje_descuento'] / 100)
                    : $precioUnitario;

                $idPromocion = $promocion['id_promocion'] ?? null;

                $queryInsertProducto = "
                    INSERT INTO pedido_$categoria (id_pedido, id_$categoria, id_promocion, cantidad, precio)
                    VALUES (?, ?, ?, ?, ?)
                ";
                $stmtProducto = $conexion->prepare($queryInsertProducto);
                if (!$stmtProducto) {
                    throw new Exception("Error al preparar la consulta para $categoria: " . $conexion->error);
                }

                $stmtProducto->bind_param(
                    'iisid',
                    $idPedido,
                    $productoId,
                    $idPromocion,
                    $cantidad,
                    $precioProcesado
                );

                if (!$stmtProducto->execute()) {
                    throw new Exception("Error al insertar en $categoria: " . $stmtProducto->error);
                }

                $stmtProducto->close();
            }
        }
    }

    // Paso 3: Descontar stock mediante el archivo descontar_stock.php
    include 'descontar_stock.php';
    descontarStock($conexion, $idPedido, $carrito);

    // Paso 4: Gestionar los puntos de recompensa
    include 'gestionar_puntos.php';
    gestionarPuntos($conexion, $idUsuario, $idPedido, $totalCompra, $puntosUsados);

    // Confirmar la transacción
    $conexion->commit();

    // Vaciar el carrito de compras
    unset($_SESSION['carrito']);

    // Redirigir a la página de confirmación
    header("Location: ../../index/index-confirmacion.php?id_pedido=$idPedido");
    exit();

} catch (Exception $e) {
    $conexion->rollback();
    error_log("Error al procesar el pago: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al procesar el pago.',
        'details' => $e->getMessage()
    ]);
    exit;
}
?>