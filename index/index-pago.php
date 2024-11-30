<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir la conexión y el header
include('../conexion.php');
include('../includes/header.php');

// Obtener los productos del carrito
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

// Calcular el total
$totalCarrito = 0;
foreach ($carrito as $categoria => $productos) {
    foreach ($productos as $productoId => $producto) {
        // Obtener detalles del producto
        $queryProducto = "SELECT precio FROM {$categoria} WHERE id_{$categoria} = $productoId";
        $resultadoProducto = $conexion->query($queryProducto);
        $detallesProducto = $resultadoProducto->fetch_assoc();

        // Calcular subtotal y total
        $precioUnitario = $detallesProducto['precio'];
        $subtotal = $precioUnitario * $producto['cantidad'];
        $totalCarrito += $subtotal;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pago-container {
            margin-top: 100px;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container pago-container">
        <h1 class="mb-4">Pagar</h1>

        <!-- Resumen del pedido -->
        <h3>Resumen del Pedido</h3>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carrito as $categoria => $productos): ?>
                    <?php foreach ($productos as $productoId => $producto): ?>
                        <?php
                        $queryProducto = "SELECT nombre_{$categoria} AS nombre, precio FROM {$categoria} WHERE id_{$categoria} = $productoId";
                        $resultadoProducto = $conexion->query($queryProducto);
                        $detallesProducto = $resultadoProducto->fetch_assoc();

                        $precioUnitario = $detallesProducto['precio'];
                        $subtotal = $precioUnitario * $producto['cantidad'];
                        ?>
                        <tr>
                            <td><?php echo $detallesProducto['nombre']; ?></td>
                            <td><?php echo $producto['cantidad']; ?></td>
                            <td>$<?php echo number_format($precioUnitario, 0, ',', '.'); ?></td>
                            <td>$<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="text-end">Total: $<?php echo number_format($totalCarrito, 0, ',', '.'); ?></h3>

        <!-- Formulario para la dirección -->
        <h3>Dirección de Entrega</h3>
        <form action="../funciones/procesar_pago.php" method="POST">
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" id="direccion" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="metodo-pago">Método de Pago:</label>
                <select name="metodo_pago" id="metodo-pago" class="form-control" required>
                    <option value="transbank">Transbank</option>
                    <option value="paypal">PayPal</option>
                    <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Confirmar Pago</button>
        </form>
    </div>
</body>
</html>