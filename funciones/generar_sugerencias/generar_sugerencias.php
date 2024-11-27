<?php
function generarSugerencias($userId) {
    include 'conexion.php';  // Asegúrate de ajustar la ruta al archivo de conexión a la base de datos

    $sql = "SELECT producto, COUNT(*) as cantidad
            FROM pedidos
            WHERE id_usuario = ?
            GROUP BY producto
            ORDER BY cantidad DESC
            LIMIT 3";  // Obtener los 3 productos más comprados

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $productos = [];

    while ($row = $result->fetch_assoc()) {
        $productos[] = $row['producto'];
    }

    $stmt->close();

    // Suponiendo que quieres sugerir productos de la misma categoría de los más comprados
    $sugerencias = [];
    foreach ($productos as $producto) {
        $sql = "SELECT p.nombre
                FROM productos p
                JOIN categorias c ON p.categoria_id = c.id
                WHERE c.id = (SELECT categoria_id FROM productos WHERE nombre = ?)
                AND p.nombre != ?
                LIMIT 3";  // Obtener 3 productos diferentes de la misma categoría

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $producto, $producto);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            if (!in_array($row['nombre'], $sugerencias)) {  // Evitar duplicados
                $sugerencias[] = $row['nombre'];
            }
        }

        $stmt->close();
    }

    return $sugerencias;
}
?>