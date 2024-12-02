<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir la conexión a la base de datos
include('../conexion.php');
include('../includes/header.php'); // Incluir el header

// Obtener promociones activas
$hoy = date('Y-m-d H:i:s');
$queryPromociones = "SELECT * FROM promocion WHERE fecha_inicio <= '$hoy' AND fecha_fin >= '$hoy'";
$resultadoPromociones = $conexion->query($queryPromociones);

$promociones = [];
if ($resultadoPromociones->num_rows > 0) {
    while ($promo = $resultadoPromociones->fetch_assoc()) {
        $promociones[] = $promo;
    }
}

// Función para calcular el precio promocional
function calcularPrecioPromocional($precio, $descuento) {
    return $precio - ($precio * $descuento / 100);
}

// Función para buscar una promoción activa para un producto
function obtenerPromocion($productoId, $categoria, $promociones) {
    foreach ($promociones as $promo) {
        if (($categoria === 'hamburguesa' && isset($promo['id_hamburguesa']) && $promo['id_hamburguesa'] == $productoId) ||
            ($categoria === 'bebida' && isset($promo['id_bebida']) && $promo['id_bebida'] == $productoId) ||
            ($categoria === 'acompaniamiento' && isset($promo['id_acompaniamiento']) && $promo['id_acompaniamiento'] == $productoId) ||
            ($categoria === 'postre' && isset($promo['id_postre']) && $promo['id_postre'] == $productoId) ||
            ($categoria === 'combo' && isset($promo['id_combo']) && $promo['id_combo'] == $productoId)) {
            return $promo;
        }
    }
    return null;
}

// Calcular el subtotal y total del carrito
$totalCarrito = 0;

// Obtener los productos del carrito desde la sesión
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .carrito-container {
            margin-top: 100px;
        }
        .table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .total-container {
            text-align: right;
        }
        .notification {
            background-color: #343a40;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container carrito-container">
        <h1 class="mb-4">Carrito de Compras</h1>

        <?php if (!empty($carrito)): ?>
            <table class="table table-bordered text-center">
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
                    <?php foreach ($carrito as $categoria => $productos): ?>
                        <?php foreach ($productos as $productoId => $producto): ?>
                            <?php
                            // Obtener detalles del producto desde la base de datos
                            $queryProducto = "SELECT nombre_{$categoria} AS nombre, precio, CONCAT('../uploads/{$categoria}s/', imagen) AS imagen FROM {$categoria} WHERE id_{$categoria} = $productoId";
                            $resultadoProducto = $conexion->query($queryProducto);
                            $detallesProducto = $resultadoProducto->fetch_assoc();

                            $promocion = obtenerPromocion($productoId, $categoria, $promociones);
                            $precioUnitario = $promocion ? calcularPrecioPromocional($detallesProducto['precio'], $promocion['porcentaje_descuento']) : $detallesProducto['precio'];

                            $subtotal = $precioUnitario * $producto['cantidad'];
                            $totalCarrito += $subtotal;
                            ?>
                            <tr>
                                <td>
                                    <img src="<?= file_exists($detallesProducto['imagen']) ? $detallesProducto['imagen'] : '../uploads/default.jpg'; ?>" alt="<?= $detallesProducto['nombre']; ?>">
                                    <p><?= $detallesProducto['nombre']; ?></p>
                                </td>
                                <td>
                                    <input type="number" class="form-control cantidad-producto" data-id="<?= $productoId; ?>" data-categoria="<?= $categoria; ?>" value="<?= $producto['cantidad']; ?>" min="1">
                                </td>
                                <td class="precio-unitario" data-precio="<?= $precioUnitario; ?>">$<?= number_format($precioUnitario, 0, ',', '.'); ?></td>
                                <td class="subtotal">$<?= number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm eliminar-producto" data-id="<?= $productoId; ?>" data-categoria="<?= $categoria; ?>">Eliminar</button>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-container">
                <h3>Total: <span class="total-carrito">$<?php echo number_format($totalCarrito, 0, ',', '.'); ?></span></h3>
                <button class="btn btn-warning vaciar-carrito">Vaciar Carrito</button>
                <?php if (!empty($carrito)): ?>
                    <a href="../index/index-pago.php" class="btn btn-success">Ir a Pagar</a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p class="text-center">Tu carrito está vacío.</p>
        <?php endif; ?>
    </div>

    <script>
        // Eliminar producto del carrito
        document.querySelectorAll('.eliminar-producto').forEach(button => {
    button.addEventListener('click', function () {
        const idProducto = this.dataset.id;
        const categoria = this.dataset.categoria;

        fetch('../funciones/gestionar_carrito/eliminar_carrito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id_producto: idProducto, categoria: categoria })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload(); // Recargar la página
            } else {
                alert(data.message); // Mostrar el mensaje de error
            }
        });
    });
});

document.querySelector('.vaciar-carrito').addEventListener('click', function () {
    fetch('../funciones/gestionar_carrito/vaciar_carrito.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    });
});

        // Actualizar cantidad del producto
        document.querySelectorAll('.cantidad-producto').forEach(input => {
            input.addEventListener('change', function () {
                const idProducto = this.dataset.id;
                const categoria = this.dataset.categoria;
                const cantidad = this.value;

                fetch('../funciones/gestionar_carrito/actualizar_carrito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        id_producto: idProducto,
                        categoria: categoria,
                        cantidad: cantidad
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Actualizar subtotal dinámicamente
                        const precioUnitario = parseFloat(this.closest('tr').querySelector('.precio-unitario').dataset.precio);
                        const subtotal = precioUnitario * cantidad;

                        // Actualizar el subtotal en la fila
                        this.closest('tr').querySelector('.subtotal').textContent = `$${subtotal.toLocaleString('es-CL', { minimumFractionDigits: 0 })}`;

                        // Recalcular el total general del carrito
                        let totalCarrito = 0;
                        document.querySelectorAll('.subtotal').forEach(subtotalElement => {
                            const valorSubtotal = parseFloat(subtotalElement.textContent.replace('$', '').replace(/\./g, ''));
                            totalCarrito += valorSubtotal;
                        });

                        // Actualizar el total general dinámicamente
                        document.querySelector('.total-carrito').textContent = `$${totalCarrito.toLocaleString('es-CL', { minimumFractionDigits: 0 })}`;

                        // Actualizar el contador del carrito en el header
                        const cartCountElement = document.getElementById('cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cartCount > 0 ? data.cartCount : '';
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>


</body>
</html>