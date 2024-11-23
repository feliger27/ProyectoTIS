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
        <tbody id="carrito-body">
            <?php if (!empty($_SESSION['carrito'])): ?>
                <?php foreach ($_SESSION['carrito'] as $tipo => $productos): ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr data-producto-id="<?= $producto['id'] ?>" data-tipo-producto="<?= $tipo ?>">
                            <td><?= $producto['nombre'] ?></td>
                            <td>
                                <input type="number" name="cantidad" value="<?= $producto['cantidad'] ?>" min="1" 
                                       class="form-control w-50 cantidad-input">
                            </td>
                            <td>$<?= $producto['precio'] ?></td>
                            <td class="subtotal">$<?= $producto['precio'] * $producto['cantidad'] ?></td>
                            <td>
                                <form action="../funciones/gestionar_carrito/eliminar_carrito.php" method="POST" class="d-inline">
                                    <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                                    <input type="hidden" name="tipo_producto" value="<?= $tipo ?>">
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
                <th colspan="3">Total</th>
                <th colspan="2" id="total">$<?= $total ?></th>
            </tr>
        </tfoot>
    </table>

    <?php
    // Almacenar el total en la sesión para que esté disponible en index-pago.php
    $_SESSION['total_carrito'] = $total;
    ?>

    <div class="text-end">
        <a href="index-pago.php" class="btn btn-success">Proceder al Pago</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cantidadInputs = document.querySelectorAll('.cantidad-input');
        
        cantidadInputs.forEach(input => {
            input.addEventListener('change', function () {
                const row = this.closest('tr');
                const productoId = row.dataset.productoId;
                const tipoProducto = row.dataset.tipoProducto;
                const nuevaCantidad = this.value;

                // Realizar solicitud AJAX para actualizar el carrito
                fetch('../funciones/gestionar_carrito/actualizar_carrito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ producto_id: productoId, tipo_producto: tipoProducto, cantidad: nuevaCantidad })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar el subtotal y el total
                        row.querySelector('.subtotal').textContent = `$${data.subtotal}`;
                        document.getElementById('total').textContent = `$${data.total}`;
                    } else {
                        alert('No se pudo actualizar el carrito.');
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar el carrito:', error);
                });
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
