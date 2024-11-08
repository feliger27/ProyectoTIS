<?php
session_start();
if (!isset($_GET['pedido_id'])) {
    die("Error: No se ha especificado un pedido.");
}

include('../conexion.php');
include('../includes/header.php');

$pedido_id = $_GET['pedido_id'];

// Obtener detalles del pedido
$query_pedido = "SELECT p.fecha_pedido, d.calle, d.ciudad, d.codigo_postal, mp.tipo_tarjeta AS metodo_pago 
                 FROM pedido AS p
                 JOIN direccion AS d ON p.id_direccion = d.id_direccion
                 LEFT JOIN metodo_pago AS mp ON p.id_metodo_pago = mp.id_pago
                 WHERE p.id_pedido = ?";
$stmt = $conexion->prepare($query_pedido);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result_pedido = $stmt->get_result();
$pedido = $result_pedido->fetch_assoc();
$stmt->close();

if (!$pedido) {
    die("Error: Pedido no encontrado.");
}
?>

<div class="container my-5">
    <h2 class="text-center">¡Pedido Confirmado!</h2>
    <p class="text-center">Tu pedido ha sido procesado exitosamente. Aquí tienes los detalles:</p>

    <div class="card mt-4">
        <div class="card-header">
            <h4>Detalles del Pedido #<?= htmlspecialchars($pedido_id) ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Fecha del Pedido:</strong> <?= htmlspecialchars($pedido['fecha_pedido']) ?></p>
            <p><strong>Dirección de Envío:</strong> <?= htmlspecialchars($pedido['calle']) ?>, <?= htmlspecialchars($pedido['ciudad']) ?>, <?= htmlspecialchars($pedido['codigo_postal']) ?></p>
            <p><strong>Método de Pago:</strong> <?= htmlspecialchars($pedido['metodo_pago'] ?? 'Efectivo') ?></p>
        </div>
    </div>

    <h4 class="mt-4">Resumen de Productos en el Pedido</h4>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            $total_sin_descuento = 0;
            $tipos = ['hamburguesa', 'acompaniamiento', 'bebida', 'postre'];

            // Mostrar productos individuales
            foreach ($tipos as $tipo) {
                $query_detalles = "SELECT p.nombre_$tipo AS nombre, pp.cantidad, pp.precio 
                                   FROM pedido_$tipo AS pp
                                   JOIN $tipo AS p ON pp.id_$tipo = p.id_$tipo
                                   WHERE pp.id_pedido = ?";
                $stmt = $conexion->prepare($query_detalles);
                $stmt->bind_param("i", $pedido_id);
                $stmt->execute();
                $result_detalles = $stmt->get_result();

                while ($detalle = $result_detalles->fetch_assoc()) {
                    $subtotal = $detalle['cantidad'] * $detalle['precio'];
                    $total += $subtotal;
                    $total_sin_descuento += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($detalle['nombre']) ?></td>
                        <td><?= ucfirst($tipo) ?></td>
                        <td><?= htmlspecialchars($detalle['cantidad']) ?></td>
                        <td>$<?= number_format($detalle['precio'], 0, ',', '.') ?></td>
                        <td>$<?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                    <?php
                }
                $stmt->close();
            }

            // Mostrar combos y sus productos
            $query_combos = "SELECT c.nombre_combo AS nombre, c.precio AS precio_combo, pc.id_combo 
                             FROM pedido_combo AS pc
                             JOIN combo AS c ON pc.id_combo = c.id_combo
                             WHERE pc.id_pedido = ?";
            $stmt_combo = $conexion->prepare($query_combos);
            $stmt_combo->bind_param("i", $pedido_id);
            $stmt_combo->execute();
            $result_combo = $stmt_combo->get_result();

            while ($combo = $result_combo->fetch_assoc()) {
                $subtotal_combo = $combo['precio_combo'];
                $total += $subtotal_combo;

                echo "<tr>
                        <td colspan='5' class='table-active'><strong>Combo: " . htmlspecialchars($combo['nombre']) . "</strong> - Precio Combo: $" . number_format($subtotal_combo, 0, ',', '.') . "</td>
                      </tr>";

                // Detalle de productos dentro del combo
                foreach ($tipos as $tipo) {
                    $query_productos_combo = "SELECT p.nombre_$tipo AS nombre, cc.cantidad, p.precio 
                                              FROM combo_$tipo AS cc
                                              JOIN $tipo AS p ON cc.id_$tipo = p.id_$tipo
                                              WHERE cc.id_combo = ?";
                    $stmt_productos_combo = $conexion->prepare($query_productos_combo);
                    $stmt_productos_combo->bind_param("i", $combo['id_combo']);
                    $stmt_productos_combo->execute();
                    $result_productos_combo = $stmt_productos_combo->get_result();

                    while ($producto_combo = $result_productos_combo->fetch_assoc()) {
                        $subtotal_producto = $producto_combo['cantidad'] * $producto_combo['precio'];
                        $total_sin_descuento += $subtotal_producto;

                        echo "<tr>
                                <td>&nbsp;&nbsp;&nbsp;" . htmlspecialchars($producto_combo['nombre']) . "</td>
                                <td>" . ucfirst($tipo) . "</td>
                                <td>" . htmlspecialchars($producto_combo['cantidad']) . "</td>
                                <td>$" . number_format($producto_combo['precio'], 0, ',', '.') . "</td>
                                <td>$" . number_format($subtotal_producto, 0, ',', '.') . "</td>
                              </tr>";
                    }
                    $stmt_productos_combo->close();
                }
            }
            $stmt_combo->close();
            ?>
        </tbody>
        <tfoot>
            <?php
            $ahorro = $total_sin_descuento - $total;
            ?>
            <tr>
                <th colspan="4" class="text-end">Total sin descuento</th>
                <th>$<?= number_format($total_sin_descuento, 0, ',', '.') ?></th>
            </tr>
            <tr>
                <th colspan="4" class="text-end">Ahorro por Combo</th>
                <th>$<?= number_format($ahorro, 0, ',', '.') ?></th>
            </tr>
            <tr>
                <th colspan="4" class="text-end">Total con descuento</th>
                <th>$<?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="text-center mt-4">
        <a href="index-menu.php" class="btn btn-primary">Volver al Menú</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
