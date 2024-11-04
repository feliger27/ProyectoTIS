<?php
session_start();
include '../conexion.php';
include '../includes/header.php';

$total = 0;
?>

<div class="container my-5">
    <h1 class="text-center">Carrito de Compras</h1>
    
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($_SESSION['carrito'])): ?>
                <?php foreach ($_SESSION['carrito'] as $tipo => $productos): ?>
                    <?php foreach ($productos as $id => $producto): ?>
                        <tr>
                            <td><?= htmlspecialchars($producto['nombre']); ?></td>
                            <td>
                                <form action="../funciones/gestionar_carrito/actualizar_carrito.php" method="POST" class="d-inline">
                                    <input type="hidden" name="producto_id" value="<?= htmlspecialchars($id); ?>">
                                    <input type="hidden" name="tipo_producto" value="<?= htmlspecialchars($tipo); ?>">
                                    <input type="number" name="cantidad" value="<?= htmlspecialchars($producto['cantidad']); ?>" min="1" class="form-control w-50 d-inline">
                                    <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                                </form>
                            </td>
                            <td>$<?= number_format($producto['precio'], 0, ',', '.'); ?></td>
                            <td>$<?= number_format($producto['precio'] * $producto['cantidad'], 0, ',', '.'); ?></td>
                            <td>
                                <form action="../funciones/gestionar_carrito/eliminar_carrito.php" method="POST" class="d-inline">
                                    <input type="hidden" name="producto_id" value="<?= htmlspecialchars($id); ?>">
                                    <input type="hidden" name="tipo_producto" value="<?= htmlspecialchars($tipo); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php $total += $producto['precio'] * $producto['cantidad']; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">El carrito está vacío</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th colspan="2">$<?= number_format($total, 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="text-end">
        <a href="index-pago.php" class="btn btn-success">Proceder al Pago</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
