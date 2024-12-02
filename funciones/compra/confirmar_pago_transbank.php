<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../vendor/autoload.php';
include '../../conexion.php';

use Transbank\Webpay\WebpayPlus\Transaction;

// Capturar el token_ws correctamente
if (isset($_POST['token_ws'])) {
    $token = $_POST['token_ws'];
} elseif (isset($_GET['token_ws'])) {
    $token = $_GET['token_ws'];
} else {
    http_response_code(400);
    echo "Token no recibido.";
    exit;
}

$transaction = new Transaction();

try {
    // Confirmar la transacción con Transbank
    $response = $transaction->commit($token);

    // Registrar la respuesta para depuración
    error_log("Respuesta de Transbank: " . print_r($response, true));

    // Verificar el estado de la transacción
    if ($response->isApproved()) {
        // Validar datos de la sesión
        if (!isset($_SESSION['transbank'])) {
            error_log("Datos de sesión faltantes.");
            echo "Error: No se encontraron los datos necesarios para procesar la transacción.";
            exit;
        }

        $idUsuario = $_SESSION['transbank']['id_usuario'];
        $idDireccion = $_SESSION['transbank']['id_direccion'];
        $totalCompra = $_SESSION['transbank']['total_compra'];
        $puntosUsados = $_SESSION['transbank']['puntos_usados'];
        $carrito = $_SESSION['transbank']['carrito'];

        // Procesar el pedido
        $conexion->begin_transaction();

        // Registrar el pedido
        $queryPedido = "
            INSERT INTO pedido (id_usuario, estado_pedido, id_direccion, fecha_pedido, monto_total) 
            VALUES (?, ?, ?, NOW(), ?)
        ";
        $stmtPedido = $conexion->prepare($queryPedido);
        $estadoPedido = 'en_preparacion';
        $stmtPedido->bind_param('isis', $idUsuario, $estadoPedido, $idDireccion, $totalCompra);
        $stmtPedido->execute();
        $idPedido = $stmtPedido->insert_id;

        foreach ($carrito as $categoria => $productos) {
            foreach ($productos as $productoId => $producto) {
                if (!isset($producto['precio'])) {
                    error_log("El índice 'precio' no está definido para el producto $productoId en la categoría $categoria.");
                    echo "Error: Información del producto incompleta. Por favor, verifica tu carrito.";
                    exit;
                }
                $cantidad = $producto['cantidad'];
                $precioUnitario = $producto['precio']; // Este acceso ahora es seguro
        
                $queryInsertProducto = "
                    INSERT INTO pedido_$categoria (id_pedido, id_$categoria, cantidad, precio)
                    VALUES (?, ?, ?, ?)
                ";
                $stmtProducto = $conexion->prepare($queryInsertProducto);
                $stmtProducto->bind_param('iiid', $idPedido, $productoId, $cantidad, $precioUnitario);
                $stmtProducto->execute();
            }
        }        

        include 'descontar_stock.php';
        descontarStock($conexion, $idPedido, $carrito);

        include 'gestionar_puntos.php';
        gestionarPuntos($conexion, $idUsuario, $idPedido, $totalCompra, $puntosUsados);

        $conexion->commit();

        // Limpiar sesión y redirigir
        unset($_SESSION['carrito'], $_SESSION['transbank']);
        header("Location: ../../index/index-confirmacion.php?id_pedido=$idPedido");
        exit;
    } else {
        throw new Exception("Transacción no aprobada: " . $response->getStatus());
    }
} catch (Exception $e) {
    error_log("Error al confirmar transacción con Transbank: " . $e->getMessage());
    echo "Error al confirmar la transacción. Por favor, intenta nuevamente.";
}
?>