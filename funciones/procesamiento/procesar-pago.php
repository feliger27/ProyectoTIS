<?php
session_start();
include '../../conexion.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: ../../login/login.php");
    exit();
}

$direccion_id = $_POST['direccion_id'] ?? null;
$metodo_pago = $_POST['metodo_pago'] ?? null;
if (!$direccion_id || !$metodo_pago) {
    die("Error: Dirección o método de pago no seleccionados.");
}

// Inicializa el total total
$total_pedido = 0;

// Crear el pedido con estado 'en_preparación'
$query = "INSERT INTO pedido (id_usuario, id_direccion, id_metodo_pago, total, fecha_pedido, estado_pedido) VALUES (?, ?, ?, ?, NOW(), 'en_preparacion')";
$stmt = $conexion->prepare($query);
$stmt->bind_param("iiid", $user_id, $direccion_id, $metodo_pago, $total_pedido);
$stmt->execute();
$pedido_id = $stmt->insert_id; // Obtiene el ID del pedido insertado
$stmt->close();

// Procesar cada tipo de producto en el carrito
foreach ($_SESSION['carrito'] as $tipo => $productos) {
    foreach ($productos as $producto_id => $producto) {
        $cantidad = $producto['cantidad'];
        $precio = $producto['precio'];

        // Calcular el total por este producto
        $total_pedido += $precio * $cantidad;

        if ($tipo === 'combo') {
            // Inserta el combo en el pedido
            $query = "INSERT INTO pedido_combo (id_pedido, id_combo) VALUES (?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ii", $pedido_id, $producto_id);
            $stmt->execute();
            $stmt->close();

            // Obtener y descontar el stock de cada elemento dentro del combo
            $tipos_en_combo = ['hamburguesa', 'acompaniamiento', 'bebida', 'postre'];
            foreach ($tipos_en_combo as $tipo_combo) {
                $query_combo = "SELECT id_$tipo_combo, cantidad FROM combo_$tipo_combo WHERE id_combo = ?";
                $stmt_combo = $conexion->prepare($query_combo);
                $stmt_combo->bind_param("i", $producto_id);
                $stmt_combo->execute();
                $result_combo = $stmt_combo->get_result();

                while ($item_combo = $result_combo->fetch_assoc()) {
                    $id_item = $item_combo["id_$tipo_combo"];
                    $cantidad_item = $item_combo['cantidad'] * $cantidad;

                    if ($tipo_combo === 'hamburguesa') {
                        // Descontar ingredientes de la hamburguesa
                        descontarIngredientesHamburguesa($conexion, $id_item, $cantidad_item);
                    } else {
                        // Descontar stock directo para acompañamientos, bebidas y postres
                        reducirStock($conexion, $tipo_combo, $id_item, $cantidad_item);
                    }
                }
                $stmt_combo->close();
            }
        } elseif ($tipo === 'hamburguesa') {
            // Insertar hamburguesa en el pedido y descontar ingredientes
            $query = "INSERT INTO pedido_hamburguesa (id_pedido, id_hamburguesa, cantidad, precio) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
            $stmt->execute();
            $stmt->close();

            descontarIngredientesHamburguesa($conexion, $producto_id, $cantidad);
        } else {
            // Insertar productos genéricos (acompaniamiento, bebida, postre) y descontar stock
            $query = "INSERT INTO pedido_$tipo (id_pedido, id_$tipo, cantidad, precio) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
            $stmt->execute();
            $stmt->close();

            reducirStock($conexion, $tipo, $producto_id, $cantidad);
        }
    }
}

// Actualiza el total total en el pedido
$query_update = "UPDATE pedido SET total = ? WHERE id_pedido = ?";
$stmt_update = $conexion->prepare($query_update);
$stmt_update->bind_param("di", $total_pedido, $pedido_id);
$stmt_update->execute();
$stmt_update->close();

// Función para descontar ingredientes y aderezos de una hamburguesa
function descontarIngredientesHamburguesa($conexion, $hamburguesa_id, $cantidad_hamburguesa) {
    // Descontar ingredientes
    $query_ingredientes = "SELECT hi.id_ingrediente, hi.cantidad AS cantidad_ingrediente 
                           FROM hamburguesa_ingrediente AS hi 
                           WHERE hi.id_hamburguesa = ?";
    $stmt_ingredientes = $conexion->prepare($query_ingredientes);
    $stmt_ingredientes->bind_param("i", $hamburguesa_id);
    $stmt_ingredientes->execute();
    $result_ingredientes = $stmt_ingredientes->get_result();

    while ($ingrediente = $result_ingredientes->fetch_assoc()) {
        $cantidad_total = $ingrediente['cantidad_ingrediente'] * $cantidad_hamburguesa;
        reducirStock($conexion, 'ingrediente', $ingrediente['id_ingrediente'], $cantidad_total);
    }
    $stmt_ingredientes->close();

    // Descontar aderezos
    $query_aderezos = "SELECT ha.id_aderezo FROM hamburguesa_aderezo AS ha WHERE ha.id_hamburguesa = ?";
    $stmt_aderezos = $conexion->prepare($query_aderezos);
    $stmt_aderezos->bind_param("i", $hamburguesa_id);
    $stmt_aderezos->execute();
    $result_aderezos = $stmt_aderezos->get_result();

    while ($aderezo = $result_aderezos->fetch_assoc()) {
        reducirStock($conexion, 'aderezo', $aderezo['id_aderezo'], $cantidad_hamburguesa);
    }
    $stmt_aderezos->close();
}

// Función para reducir el stock de un producto genérico
function reducirStock($conexion, $tipo, $producto_id, $cantidad) {
    $query_stock = "UPDATE $tipo SET cantidad = cantidad - ? WHERE id_$tipo = ?";
    $stmt_stock = $conexion->prepare($query_stock);
    $stmt_stock->bind_param("ii", $cantidad, $producto_id);
    $stmt_stock->execute();
    $stmt_stock->close();
}

unset($_SESSION['carrito']);
header("Location: ../../index/index-confirmacion.php?pedido_id=" . $pedido_id);
exit();


