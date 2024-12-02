<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Valoración</title>
    <link rel="stylesheet" href="../../estilos.css"> <!-- Añade tus estilos aquí -->
</head>
<body>
    <?php
    include_once '../../conexion.php';
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: ../login/login.php");
        exit();
    }

    // Obtener el ID del pedido (puedes ajustarlo según tu lógica)
    $id_pedido = $_GET['id_pedido'] ?? null;

    if (!$id_pedido) {
        echo "<p>No se proporcionó un ID de pedido válido.</p>";
        exit();
    }

    // Consultar productos asociados al pedido
    $productos = [
        'hamburguesa' => [],
        'postre' => [],
        'bebida' => [],
        'acompaniamiento' => [],
        'combo' => [],
    ];

    function obtenerProductos($conexion, $id_pedido, $tabla, $campo_id, $campo_nombre) {
        $query = "SELECT $campo_id AS id, $campo_nombre AS nombre FROM $tabla WHERE id_pedido = ?";
        $stmt = $conexion->prepare($query);
        $productos = [];

        if ($stmt) {
            $stmt->bind_param("i", $id_pedido);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
            $stmt->close();
        }

        return $productos;
    }

    // Obtener productos asociados
    $productos['hamburguesa'] = obtenerProductos($conexion, $id_pedido, 'pedido_hamburguesa', 'id_hamburguesa', 'nombre_hamburguesa');
    $productos['postre'] = obtenerProductos($conexion, $id_pedido, 'pedido_postre', 'id_postre', 'nombre_postre');
    $productos['bebida'] = obtenerProductos($conexion, $id_pedido, 'pedido_bebida', 'id_bebida', 'nombre_bebida');
    $productos['acompaniamiento'] = obtenerProductos($conexion, $id_pedido, 'pedido_acompaniamiento', 'id_acompaniamiento', 'nombre_acompaniamiento');
    $productos['combo'] = obtenerProductos($conexion, $id_pedido, 'pedido_combo', 'id_combo', 'nombre_combo');
    ?>

    <form action="agregar_valoracion.php" method="POST">
        <h1>Agregar Valoración para Pedido #<?php echo htmlspecialchars($id_pedido); ?></h1>

        <input type="hidden" name="id_pedido" value="<?php echo htmlspecialchars($id_pedido); ?>">

        <?php foreach ($productos as $tipo => $items): ?>
            <?php if (!empty($items)): ?>
                <fieldset>
                    <legend>Valoraciones para <?php echo ucfirst($tipo); ?></legend>
                    <?php foreach ($items as $producto): ?>
                        <div>
                            <label>
                                <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                                <input type="hidden" name="valoraciones[<?php echo $tipo; ?>][<?php echo $producto['id']; ?>][nombre]" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            </label>
                            <label for="estrellas_<?php echo $tipo . '_' . $producto['id']; ?>">Estrellas (1-5):</label>
                            <input type="number" id="estrellas_<?php echo $tipo . '_' . $producto['id']; ?>" name="valoraciones[<?php echo $tipo; ?>][<?php echo $producto['id']; ?>][estrellas]" min="1" max="5" required>
                            <label for="comentario_<?php echo $tipo . '_' . $producto['id']; ?>">Comentario:</label>
                            <textarea id="comentario_<?php echo $tipo . '_' . $producto['id']; ?>" name="valoraciones[<?php echo $tipo; ?>][<?php echo $producto['id']; ?>][comentario]" placeholder="Escribe tu opinión"></textarea>
                        </div>
                    <?php endforeach; ?>
                </fieldset>
            <?php endif; ?>
        <?php endforeach; ?>

        <button type="submit">Enviar Valoraciones</button>
    </form>
</body>
</html>
