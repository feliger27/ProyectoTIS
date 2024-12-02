<?php
session_start();
include_once '../../conexion.php'; // Incluye la conexión a la base de datos

// Verificación de que el usuario esté logueado y tenga un carrito
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || empty($_SESSION['carrito'])) {
    header("Location: ../../index/index-carrito.php?error=carrito_vacio");
    exit();
}

// Obtener los datos enviados por el formulario
$direccion_id = $_POST['direccion_id'] ?? null;
$metodo_pago = $_POST['metodo_pago'] ?? null;

// Variables para un nuevo método de pago
$tipo_tarjeta = $_POST['tipo_tarjeta'] ?? null;
$numero_tarjeta = $_POST['numero_tarjeta'] ?? null;
$nombre_titular = $_POST['nombre_titular'] ?? null;
$mes_expiracion = $_POST['mes_expiracion'] ?? null;
$anio_expiracion = $_POST['anio_expiracion'] ?? null;
$cvv = $_POST['cvv'] ?? null;
$recordar_metodo_pago = isset($_POST['recordar_metodo_pago']);

// Verificación si el método de pago es "Efectivo"
if ($metodo_pago === "efectivo") {
    $metodo_pago = null; // No se requiere ID para "Efectivo"
} else {
    // Si el usuario seleccionó "Agregar Nuevo Método de Pago"
    if (!$metodo_pago && $tipo_tarjeta && $numero_tarjeta && $nombre_titular && $mes_expiracion && $anio_expiracion && $cvv) {
        // Formato de fecha de expiración (solo mes y año)
        $fecha_expiracion = "$anio_expiracion-$mes_expiracion-01";
        
        // Insertar el nuevo método de pago en la base de datos
        $query_insert_pago = "INSERT INTO metodo_pago (tipo_tarjeta, numero_tarjeta, fecha_expiracion, cvv, nombre_titular) VALUES (?, ?, ?, ?, ?)";
        $stmt_pago = $conexion->prepare($query_insert_pago);
        $stmt_pago->bind_param("sssss", $tipo_tarjeta, $numero_tarjeta, $fecha_expiracion, $cvv, $nombre_titular);
        $stmt_pago->execute();
        $metodo_pago = $stmt_pago->insert_id; // Obtener el ID del nuevo método de pago
        $stmt_pago->close();

        // Si el usuario desea recordar el método de pago, lo asociamos a su cuenta
        if ($recordar_metodo_pago) {
            $query_usuario_pago = "INSERT INTO usuario_metodo_pago (id_usuario, id_pago) VALUES (?, ?)";
            $stmt_usuario_pago = $conexion->prepare($query_usuario_pago);
            $stmt_usuario_pago->bind_param("ii", $user_id, $metodo_pago);
            $stmt_usuario_pago->execute();
            $stmt_usuario_pago->close();
        }
    }
}

// Crear el pedido en la base de datos
$total = $_SESSION['total_carrito'] ?? 0;
$estado_pedido = 'en_preparacion';
$fecha_pedido = date("Y-m-d H:i:s");

$query_pedido = "INSERT INTO pedido (id_usuario, total, estado_pedido, id_direccion, id_metodo_pago, fecha_pedido) VALUES (?, ?, ?, ?, ?, ?)";
$stmt_pedido = $conexion->prepare($query_pedido);
$stmt_pedido->bind_param("idssis", $user_id, $total, $estado_pedido, $direccion_id, $metodo_pago, $fecha_pedido);
$stmt_pedido->execute();
$pedido_id = $stmt_pedido->insert_id;
$stmt_pedido->close();

// Procesar los elementos del carrito y ajustar el stock
foreach ($_SESSION['carrito'] as $tipo => $productos) {
    foreach ($productos as $producto_id => $producto) {
        $cantidad = $producto['cantidad'];
        $precio = $producto['precio'];

        switch ($tipo) {
            case 'hamburguesa':
                // Insertar hamburguesa en el pedido y ajustar ingredientes
                $query_pedido_hamburguesa = "INSERT INTO pedido_hamburguesa (id_pedido, id_hamburguesa, cantidad, precio) VALUES (?, ?, ?, ?)";
                $stmt_hamburguesa = $conexion->prepare($query_pedido_hamburguesa);
                $stmt_hamburguesa->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
                $stmt_hamburguesa->execute();
                $stmt_hamburguesa->close();

                // Ajuste de stock de ingredientes
                $query_ingredientes = "SELECT hi.id_ingrediente, hi.cantidad AS cantidad_por_hamburguesa FROM hamburguesa_ingrediente hi WHERE hi.id_hamburguesa = ?";
                $stmt_ingredientes = $conexion->prepare($query_ingredientes);
                $stmt_ingredientes->bind_param("i", $producto_id);
                $stmt_ingredientes->execute();
                $result_ingredientes = $stmt_ingredientes->get_result();

                while ($ingrediente = $result_ingredientes->fetch_assoc()) {
                    $id_ingrediente = $ingrediente['id_ingrediente'];
                    $cantidad_necesaria = $ingrediente['cantidad_por_hamburguesa'] * $cantidad;

                    // Actualizar stock de ingredientes
                    $query_update_ingrediente = "UPDATE ingrediente SET cantidad = cantidad - ? WHERE id_ingrediente = ?";
                    $stmt_update_ingrediente = $conexion->prepare($query_update_ingrediente);
                    $stmt_update_ingrediente->bind_param("ii", $cantidad_necesaria, $id_ingrediente);
                    $stmt_update_ingrediente->execute();
                    $stmt_update_ingrediente->close();
                }
                $stmt_ingredientes->close();
                break;

            case 'acompaniamiento':
                $query_pedido_acompaniamiento = "INSERT INTO pedido_acompaniamiento (id_pedido, id_acompaniamiento, cantidad, precio) VALUES (?, ?, ?, ?)";
                $stmt_acompaniamiento = $conexion->prepare($query_pedido_acompaniamiento);
                $stmt_acompaniamiento->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
                $stmt_acompaniamiento->execute();
                $stmt_acompaniamiento->close();

                // Actualizar stock
                $query_update_acompaniamiento = "UPDATE acompaniamiento SET cantidad = cantidad - ? WHERE id_acompaniamiento = ?";
                $stmt_update_acompaniamiento = $conexion->prepare($query_update_acompaniamiento);
                $stmt_update_acompaniamiento->bind_param("ii", $cantidad, $producto_id);
                $stmt_update_acompaniamiento->execute();
                $stmt_update_acompaniamiento->close();
                break;

            case 'bebida':
                $query_pedido_bebida = "INSERT INTO pedido_bebida (id_pedido, id_bebida, cantidad, precio) VALUES (?, ?, ?, ?)";
                $stmt_bebida = $conexion->prepare($query_pedido_bebida);
                $stmt_bebida->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
                $stmt_bebida->execute();
                $stmt_bebida->close();

                // Actualizar stock
                $query_update_bebida = "UPDATE bebida SET cantidad = cantidad - ? WHERE id_bebida = ?";
                $stmt_update_bebida = $conexion->prepare($query_update_bebida);
                $stmt_update_bebida->bind_param("ii", $cantidad, $producto_id);
                $stmt_update_bebida->execute();
                $stmt_update_bebida->close();
                break;

            case 'postre':
                $query_pedido_postre = "INSERT INTO pedido_postre (id_pedido, id_postre, cantidad, precio) VALUES (?, ?, ?, ?)";
                $stmt_postre = $conexion->prepare($query_pedido_postre);
                $stmt_postre->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
                $stmt_postre->execute();
                $stmt_postre->close();

                // Actualizar stock
                $query_update_postre = "UPDATE postre SET cantidad = cantidad - ? WHERE id_postre = ?";
                $stmt_update_postre = $conexion->prepare($query_update_postre);
                $stmt_update_postre->bind_param("ii", $cantidad, $producto_id);
                $stmt_update_postre->execute();
                $stmt_update_postre->close();
                break;

            case 'combo':
                $query_pedido_combo = "INSERT INTO pedido_combo (id_pedido, id_combo) VALUES (?, ?)";
                $stmt_combo = $conexion->prepare($query_pedido_combo);
                $stmt_combo->bind_param("ii", $pedido_id, $producto_id);
                $stmt_combo->execute();
                $stmt_combo->close();

                // Descuento de stock para los elementos del combo
                $query_combo_hamburguesas = "SELECT id_hamburguesa, cantidad FROM combo_hamburguesa WHERE id_combo = ?";
                $stmt_combo_hamburguesas = $conexion->prepare($query_combo_hamburguesas);
                $stmt_combo_hamburguesas->bind_param("i", $producto_id);
                $stmt_combo_hamburguesas->execute();
                $result_combo_hamburguesas = $stmt_combo_hamburguesas->get_result();

                while ($hamburguesa = $result_combo_hamburguesas->fetch_assoc()) {
                    $id_hamburguesa = $hamburguesa['id_hamburguesa'];
                    $cantidad_hamburguesa = $hamburguesa['cantidad'] * $cantidad;

                    $query_combo_hamburguesa_ingredientes = "SELECT hi.id_ingrediente, hi.cantidad AS cantidad_por_hamburguesa 
                                                             FROM hamburguesa_ingrediente hi 
                                                             WHERE hi.id_hamburguesa = ?";
                    $stmt_hamburguesa_ingredientes = $conexion->prepare($query_combo_hamburguesa_ingredientes);
                    $stmt_hamburguesa_ingredientes->bind_param("i", $id_hamburguesa);
                    $stmt_hamburguesa_ingredientes->execute();
                    $result_hamburguesa_ingredientes = $stmt_hamburguesa_ingredientes->get_result();

                    while ($ingrediente = $result_hamburguesa_ingredientes->fetch_assoc()) {
                        $id_ingrediente = $ingrediente['id_ingrediente'];
                        $cantidad_ingrediente = $ingrediente['cantidad_por_hamburguesa'] * $cantidad_hamburguesa;

                        $query_update_ingrediente = "UPDATE ingrediente SET cantidad = cantidad - ? WHERE id_ingrediente = ?";
                        $stmt_update_ingrediente = $conexion->prepare($query_update_ingrediente);
                        $stmt_update_ingrediente->bind_param("ii", $cantidad_ingrediente, $id_ingrediente);
                        $stmt_update_ingrediente->execute();
                        $stmt_update_ingrediente->close();
                    }
                    $stmt_hamburguesa_ingredientes->close();
                }
                $stmt_combo_hamburguesas->close();
                break;
        }
    }
}

// Calcula los puntos en base al total
$puntos_ganados = floor($total / 1000);  // 1 punto por cada 1000 pesos

// Actualiza los puntos del usuario
$query_update_puntos = "UPDATE usuario SET puntos_recompensa = puntos_recompensa + ? WHERE id_usuario = ?";
$stmt_update_puntos = $conexion->prepare($query_update_puntos);
$stmt_update_puntos->bind_param("ii", $puntos_ganados, $user_id);
$stmt_update_puntos->execute();
$stmt_update_puntos->close();

// Registrar los puntos en la tabla de recompensas
$query_recompensa = "INSERT INTO recompensa (id_usuario, id_pedido, cantidad_recompensada) VALUES (?, ?, ?)";
$stmt_recompensa = $conexion->prepare($query_recompensa);
$stmt_recompensa->bind_param("iii", $user_id, $pedido_id, $puntos_ganados);
$stmt_recompensa->execute();
$stmt_recompensa->close();


// Limpiar carrito y redirigir a confirmación
unset($_SESSION['carrito']);
header("Location: ../../index/index-confirmacion.php?pedido_id=$pedido_id");
exit();
