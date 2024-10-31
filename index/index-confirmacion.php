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

    <h4 class="mt-4">Productos en el Pedido</h4>
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
            $tipos = ['hamburguesa', 'acompaniamiento', 'bebida', 'postre'];

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
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Total</th>
                <th>$<?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="text-center mt-4">
        <a href="index-menu.php" class="btn btn-primary">Volver al Menú</a>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
