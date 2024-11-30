<?php
function descontarStock($conexion, $idPedido, $carrito) {
    try {
        foreach ($carrito as $categoria => $productos) {
            foreach ($productos as $productoId => $producto) {
                $cantidad = $producto['cantidad'];

                if ($categoria === 'acompaniamiento' || $categoria === 'bebida' || $categoria === 'postre') {
                    $queryDescuentoStock = "
                        UPDATE $categoria 
                        SET cantidad = cantidad - ? 
                        WHERE id_$categoria = ?
                    ";
                    $stmtDescuento = $conexion->prepare($queryDescuentoStock);
                    $stmtDescuento->bind_param('ii', $cantidad, $productoId);
                    $stmtDescuento->execute();
                    $stmtDescuento->close();
                }

                if ($categoria === 'hamburguesa') {
                    descontarHamburguesa($conexion, $productoId, $cantidad);
                }

                if ($categoria === 'combo') {
                    descontarCombo($conexion, $productoId, $cantidad);
                }
            }
        }
    } catch (Exception $e) {
        throw new Exception("Error al descontar stock: " . $e->getMessage());
    }
}

function descontarHamburguesa($conexion, $idHamburguesa, $cantidad) {
    // Descontar ingredientes
    $queryIngredientes = "
        SELECT id_ingrediente, cantidad AS necesario 
        FROM hamburguesa_ingrediente 
        WHERE id_hamburguesa = ?
    ";
    $stmtIngredientes = $conexion->prepare($queryIngredientes);
    $stmtIngredientes->bind_param('i', $idHamburguesa);
    $stmtIngredientes->execute();
    $resultIngredientes = $stmtIngredientes->get_result();

    while ($ingrediente = $resultIngredientes->fetch_assoc()) {
        $idIngrediente = $ingrediente['id_ingrediente'];
        $cantidadNecesaria = $ingrediente['necesario'] * $cantidad;
        $conexion->query("
            UPDATE ingrediente 
            SET cantidad = cantidad - $cantidadNecesaria 
            WHERE id_ingrediente = $idIngrediente
        ");
    }
    $stmtIngredientes->close();

    // Descontar aderezos
    $queryAderezos = "
        SELECT id_aderezo 
        FROM hamburguesa_aderezo 
        WHERE id_hamburguesa = ?
    ";
    $stmtAderezos = $conexion->prepare($queryAderezos);
    $stmtAderezos->bind_param('i', $idHamburguesa);
    $stmtAderezos->execute();
    $resultAderezos = $stmtAderezos->get_result();

    while ($aderezo = $resultAderezos->fetch_assoc()) {
        $idAderezo = $aderezo['id_aderezo'];
        $conexion->query("
            UPDATE aderezo 
            SET cantidad = cantidad - $cantidad 
            WHERE id_aderezo = $idAderezo
        ");
    }
    $stmtAderezos->close();
}

function descontarCombo($conexion, $idCombo, $cantidadCombo) {
    // Procesar hamburguesas del combo
    descontarItemsCombo($conexion, $idCombo, $cantidadCombo, 'hamburguesa');
    descontarItemsCombo($conexion, $idCombo, $cantidadCombo, 'acompaniamiento');
    descontarItemsCombo($conexion, $idCombo, $cantidadCombo, 'bebida');
    descontarItemsCombo($conexion, $idCombo, $cantidadCombo, 'postre');
}

function descontarItemsCombo($conexion, $idCombo, $cantidadCombo, $categoria) {
    $queryItemsCombo = "
        SELECT id_$categoria, cantidad 
        FROM combo_$categoria 
        WHERE id_combo = ?
    ";
    $stmtItemsCombo = $conexion->prepare($queryItemsCombo);
    $stmtItemsCombo->bind_param('i', $idCombo);
    $stmtItemsCombo->execute();
    $resultItemsCombo = $stmtItemsCombo->get_result();

    while ($item = $resultItemsCombo->fetch_assoc()) {
        $idItem = $item["id_$categoria"];
        $cantidadNecesaria = $item['cantidad'] * $cantidadCombo;

        if ($categoria === 'hamburguesa') {
            descontarHamburguesa($conexion, $idItem, $cantidadNecesaria);
        } else {
            $conexion->query("
                UPDATE $categoria 
                SET cantidad = cantidad - $cantidadNecesaria 
                WHERE id_$categoria = $idItem
            ");
        }
    }
    $stmtItemsCombo->close();
}