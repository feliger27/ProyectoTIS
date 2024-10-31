<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

include('../../conexion.php');

$user_id = $_SESSION['user_id'];

// Validación de los datos de dirección y método de pago
if (!isset($_POST['direccion_id']) && empty($_POST['nueva_calle'])) {
    die("Error: No se ha seleccionado una dirección.");
}
if (!isset($_POST['metodo_pago'])) {
    die("Error: No se ha seleccionado un método de pago.");
}

// Procesar nueva dirección si se ingresó una
if (!empty($_POST['nueva_calle']) && !empty($_POST['nueva_ciudad']) && !empty($_POST['nuevo_codigo_postal'])) {
    $calle = $_POST['nueva_calle'];
    $ciudad = $_POST['nueva_ciudad'];
    $codigo_postal = $_POST['nuevo_codigo_postal'];

    $query = "INSERT INTO direccion (calle, ciudad, codigo_postal) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssi", $calle, $ciudad, $codigo_postal);
    $stmt->execute();
    $direccion_id = $stmt->insert_id;
    $stmt->close();

    if (isset($_POST['recordar_direccion'])) {
        $query = "INSERT INTO direccion_usuario (id_usuario, id_direccion) VALUES (?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $user_id, $direccion_id);
        $stmt->execute();
        $stmt->close();
    }
} else {
    $direccion_id = $_POST['direccion_id'];
}

// Procesar el método de pago
$metodo_pago = $_POST['metodo_pago'];
$metodo_pago_id = null;

if ($metodo_pago === 'debito' || $metodo_pago === 'credito') {
    $nombre_tarjeta = $_POST['nombre_tarjeta'];
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $codigo_seguridad = $_POST['codigo_seguridad'];
    $cuotas = $metodo_pago === 'credito' ? $_POST['num_cuotas'] : null;

    $query = "INSERT INTO metodo_pago (tipo, nombre_tarjeta, numero_tarjeta, fecha_vencimiento, codigo_seguridad, cuotas) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssssi", $metodo_pago, $nombre_tarjeta, $numero_tarjeta, $fecha_vencimiento, $codigo_seguridad, $cuotas);
    $stmt->execute();
    $metodo_pago_id = $stmt->insert_id;
    $stmt->close();

    if (isset($_POST['recordar_metodo_pago'])) {
        $query = "INSERT INTO usuario_metodo_pago (id_usuario, id_metodo_pago) VALUES (?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $user_id, $metodo_pago_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Crear el pedido en la base de datos
$query = "INSERT INTO pedido (id_usuario, id_direccion, id_metodo_pago, fecha_pedido) VALUES (?, ?, ?, NOW())";
$stmt = $conexion->prepare($query);
$stmt->bind_param("iii", $user_id, $direccion_id, $metodo_pago_id);
$stmt->execute();
$pedido_id = $stmt->insert_id;
$stmt->close();

// Procesar los productos del carrito y ajustar el stock
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $tipo => $productos) {
        foreach ($productos as $producto) {
            $producto_id = $producto['id'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio'];

            $query = "INSERT INTO pedido_$tipo (id_pedido, id_$tipo, cantidad, precio) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
            $stmt->execute();
            $stmt->close();

            if ($tipo === 'hamburguesa') {
                // Descontar ingredientes de la hamburguesa
                $query = "SELECT id_ingrediente, cantidad FROM hamburguesa_ingrediente WHERE id_hamburguesa = ?";
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("i", $producto_id);
                $stmt->execute();
                $ingredientes = $stmt->get_result();
                while ($ingrediente = $ingredientes->fetch_assoc()) {
                    $id_ingrediente = $ingrediente['id_ingrediente'];
                    $cantidad_ingrediente = $ingrediente['cantidad'] * $cantidad;
                    $update_query = "UPDATE ingrediente SET cantidad = cantidad - ? WHERE id_ingrediente = ?";
                    $update_stmt = $conexion->prepare($update_query);
                    $update_stmt->bind_param("ii", $cantidad_ingrediente, $id_ingrediente);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                $stmt->close();

                $query = "SELECT id_aderezo FROM hamburguesa_aderezo WHERE id_hamburguesa = ?";
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("i", $producto_id);
                $stmt->execute();
                $aderezos = $stmt->get_result();
                while ($aderezo = $aderezos->fetch_assoc()) {
                    $id_aderezo = $aderezo['id_aderezo'];
                    $cantidad_aderezo = $cantidad;
                    $update_query = "UPDATE aderezo SET cantidad = cantidad - ? WHERE id_aderezo = ?";
                    $update_stmt = $conexion->prepare($update_query);
                    $update_stmt->bind_param("ii", $cantidad_aderezo, $id_aderezo);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                $stmt->close();
            } else {
                $query = "UPDATE $tipo SET cantidad = cantidad - ? WHERE id_$tipo = ?";
                $stmt = $conexion->prepare($query);
                $stmt->bind_param("ii", $cantidad, $producto_id);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

// Limpiar el carrito de la sesión después de procesar el pedido
unset($_SESSION['carrito']);

// Redirigir a la página de confirmación de pedido
header("Location: ../../index/index-confirmacion.php?pedido_id=" . $pedido_id);
exit();
?>
