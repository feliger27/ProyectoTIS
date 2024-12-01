<?php

function generarSugerencias($userId) {
    include '../conexion.php';  // Asegúrate de ajustar la ruta al archivo de conexión

    // Paso 1: Obtener los productos más comprados por el usuario
    $sql = "SELECT h.nombre_hamburguesa, h.imagen, COUNT(ph.id_hamburguesa) AS cantidad
            FROM pedido_hamburguesa ph
            JOIN hamburguesa h ON ph.id_hamburguesa = h.id_hamburguesa
            JOIN pedido p ON ph.id_pedido = p.id_pedido
            WHERE p.id_usuario = ?
            GROUP BY h.id_hamburguesa
            ORDER BY cantidad DESC
            LIMIT 5";  // Obtener los 3 productos más comprados

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Almacenamos los productos más comprados
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = [
            'nombre' => $row['nombre_hamburguesa'],
            'imagen' => $row['imagen'] // Guardamos también la imagen
        ];
    }
    $stmt->close();

    // Paso 2: Obtener sugerencias basadas en los productos más comprados
    $sugerencias = [];
    foreach ($productos as $producto) {
        // Obtener productos similares que no sean los más comprados (por nombre)
        $sql = "SELECT h.nombre_hamburguesa, h.imagen
                FROM hamburguesa h
                WHERE h.nombre_hamburguesa != ?
                LIMIT 5";  // Obtener 3 productos diferentes

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $producto['nombre']);
        $stmt->execute();
        $result = $stmt->get_result();

        // Añadir las sugerencias
        while ($row = $result->fetch_assoc()) {
            if (!in_array($row['nombre_hamburguesa'], array_column($sugerencias, 'nombre'))) {  // Evitar duplicados
                $sugerencias[] = [
                    'nombre' => $row['nombre_hamburguesa'],
                    'imagen' => $row['imagen'] // Añadir imagen de la sugerencia
                ];
            }
        }

        $stmt->close();
    }

    // Paso 3: Sugerir productos del mismo pedido anterior (opción para volver a pedir)
    $sql = "SELECT ph.id_hamburguesa, ph.cantidad, ph.precio, h.nombre_hamburguesa, h.imagen
            FROM pedido_hamburguesa ph
            JOIN hamburguesa h ON ph.id_hamburguesa = h.id_hamburguesa
            JOIN pedido p ON ph.id_pedido = p.id_pedido
            WHERE p.id_usuario = ?
            ORDER BY ph.id_pedido DESC LIMIT 1";  // Obtener el último pedido realizado por el usuario

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si encontramos un pedido previo, agregamos la opción de volver a pedir lo mismo
    while ($row = $result->fetch_assoc()) {
        $sugerencias[] = [
            'nombre' => "Repetir: " . $row['nombre_hamburguesa'] . " (Cantidad: " . $row['cantidad'] . ", Precio: " . $row['precio'] . ")",
            'imagen' => $row['imagen'] // Añadir imagen de la hamburguesa repetida
        ];
    }
    $stmt->close();

    return $sugerencias;
}

?>
