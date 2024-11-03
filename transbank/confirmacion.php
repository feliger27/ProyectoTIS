<?php
// confirmacion.php

include('../../conexion.php'); // Conexión a la base de datos

// Obtener la respuesta de Transbank y verificar el estado del pago
$response = $_POST; // Aquí obtienes la respuesta de Transbank (asegúrate de recibir los datos correctos)
file_put_contents("log.txt", json_encode($response), FILE_APPEND); // Guardar respuesta en un log

// Verificar si el pago fue exitoso
if ($response['status'] === 'APROBADO') {
    session_start();
    $user_id = $_SESSION['user_id'];
    $conexion->begin_transaction();

    try {
        // ID de dirección y método de pago obtenidos previamente
        $direccion_id = $_SESSION['direccion_id'];
        $metodo_pago_id = $_SESSION['metodo_pago_id'];

        // Crear el pedido en la base de datos con estado "en preparación"
        $query = "INSERT INTO pedido (id_usuario, id_direccion, id_metodo_pago, fecha_pedido, estado) VALUES (?, ?, ?, NOW(), 'en preparación')";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iii", $user_id, $direccion_id, $metodo_pago_id);
        $stmt->execute();
        $pedido_id = $stmt->insert_id;
        $stmt->close();

        // Log para verificar si se creó el pedido
        file_put_contents("log.txt", "Pedido creado con ID: " . $pedido_id . "\n", FILE_APPEND);

        // Procesar los productos del carrito y ajustar el stock
        if (!empty($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $tipo => $productos) {
                foreach ($productos as $producto) {
                    $producto_id = $producto['id'];
                    $cantidad = $producto['cantidad'];
                    $precio = $producto['precio'];

                    // Insertar el pedido en la tabla específica de productos
                    $query = "INSERT INTO pedido_$tipo (id_pedido, id_$tipo, cantidad, precio) VALUES (?, ?, ?, ?)";
                    $stmt = $conexion->prepare($query);
                    $stmt->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio);
                    $stmt->execute();
                    $stmt->close();

                    file_put_contents("log.txt", "Producto insertado en pedido: $producto_id, tipo: $tipo, cantidad: $cantidad\n", FILE_APPEND);

                    if ($tipo === 'hamburguesa') {
                        // Descontar ingredientes de la hamburguesa
                        $query = "SELECT id_ingrediente, cantidad FROM hamburguesa_ingrediente WHERE id_hamburguesa = ?";
                        $stmt = $conexion->prepare($query);
                        $stmt->bind_param("i", $producto_id);
                        $stmt->execute();
                        $ingredientes = $stmt->get_result();

                        while ($ingrediente = $ingredientes->fetch_assoc()) {
                            $id_ingrediente = $ingrediente['id_ingrediente'];
                            $cantidad_ingrediente = $ingrediente['cantidad'] * $cantidad; // Cantidad total a descontar

                            // Log para verificar el ingrediente y cantidad a descontar
                            file_put_contents("log.txt", "Descontando $cantidad_ingrediente de ingrediente ID: $id_ingrediente\n", FILE_APPEND);

                            // Descontar del inventario de ingredientes
                            $update_query = "UPDATE ingrediente SET cantidad = cantidad - ? WHERE id_ingrediente = ?";
                            $update_stmt = $conexion->prepare($update_query);
                            $update_stmt->bind_param("ii", $cantidad_ingrediente, $id_ingrediente);
                            $update_stmt->execute();
                            $update_stmt->close();

                            // Log de confirmación de descuento
                            file_put_contents("log.txt", "Ingrediente ID $id_ingrediente descontado exitosamente.\n", FILE_APPEND);
                        }
                        $stmt->close();

                        // Descontar aderezos de la hamburguesa
                        $query = "SELECT id_aderezo FROM hamburguesa_aderezo WHERE id_hamburguesa = ?";
                        $stmt = $conexion->prepare($query);
                        $stmt->bind_param("i", $producto_id);
                        $stmt->execute();
                        $aderezos = $stmt->get_result();

                        while ($aderezo = $aderezos->fetch_assoc()) {
                            $id_aderezo = $aderezo['id_aderezo'];
                            $cantidad_aderezo = $cantidad; // Cantidad total a descontar

                            // Log para verificar el aderezo y cantidad a descontar
                            file_put_contents("log.txt", "Descontando $cantidad_aderezo de aderezo ID: $id_aderezo\n", FILE_APPEND);

                            // Descontar del inventario de aderezos
                            $update_query = "UPDATE aderezo SET cantidad = cantidad - ? WHERE id_aderezo = ?";
                            $update_stmt = $conexion->prepare($update_query);
                            $update_stmt->bind_param("ii", $cantidad_aderezo, $id_aderezo);
                            $update_stmt->execute();
                            $update_stmt->close();

                            // Log de confirmación de descuento
                            file_put_contents("log.txt", "Aderezo ID $id_aderezo descontado exitosamente.\n", FILE_APPEND);
                        }
                        $stmt->close();
                    } else {
                        // Descontar de otros productos
                        $query = "UPDATE $tipo SET cantidad = cantidad - ? WHERE id_$tipo = ?";
                        $stmt = $conexion->prepare($query);
                        $stmt->bind_param("ii", $cantidad, $producto_id);
                        $stmt->execute();
                        $stmt->close();

                        // Log de confirmación para otros productos
                        file_put_contents("log.txt", "Descuento realizado en inventario para producto ID: $producto_id, tipo: $tipo\n", FILE_APPEND);
                    }
                }
            }
        }

        // Vaciar el carrito de la sesión después de procesar el pedido
        unset($_SESSION['carrito']);

        // Confirmar la transacción en la base de datos
        $conexion->commit();

        echo "Pago confirmado y pedido procesado con éxito. El carrito ha sido vaciado.";

    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $conexion->rollback();
        file_put_contents("log.txt", "Error en el procesamiento del pedido: " . $e->getMessage() . "\n", FILE_APPEND);
        echo "Error en el procesamiento del pedido: " . $e->getMessage();
    }
} else {
    echo "El pago no fue aprobado. Estado: " . $response['status'];
}
?>
