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
    <title>Mi Carrito - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            margin-top: 90px;
        }
        .carrito-container {
            margin: 50px auto;
            max-width: 900px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        .btn-vaciar, .btn-pagar {
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }
        .btn-vaciar {
            font-size: 18px;
            background-color: #dc3545;
            color: white;
        }
        .btn-vaciar:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }
        .btn-pagar {
            font-size: 18px;
            background-color: #28a745;
            color: white;
        }
        .btn-pagar:hover {
            background-color: #218838;
            transform: scale(1.1);
        }
        .table thead th {
            background-color: #d35400;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }
        .table td {
            vertical-align: middle;
        }
        .price-normal {
            color: #7f8c8d;
            text-decoration: line-through;
            margin-right: 5px;
        }
        .price-promo {
            color: #27ae60;
            font-weight: bold;
        }
        .subtotal {
            font-weight: bold !important;
        }
        .header-title {
            font-size: 35px;
            font-weight: bold;
            color: #d35400;
            text-align: center;
            margin-bottom: 20px;
        }
        .icon-trash {
            font-size: 20px;
            color: #e74c3c;
            cursor: pointer;
        }
        .icon-trash:hover {
            color: #c0392b;
        }

        .total {
            font-size: 25px;
        }
    </style>
</head>
<body>
    <div class="container carrito-container">
        <h2 class="header-title text-center mb-4">Mi Carrito</h2>

        <?php if (!empty($carrito)): ?>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
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

                            // Calcular precio y subtotal
                            $promocion = obtenerPromocion($productoId, $categoria, $promociones);
                            $precioUnitario = $promocion ? calcularPrecioPromocional($detallesProducto['precio'], $promocion['porcentaje_descuento']) : $detallesProducto['precio'];
                            $subtotal = $precioUnitario * $producto['cantidad'];
                            $totalCarrito += $subtotal;
                            ?>
                            <tr>
                                <td><img src="<?= file_exists($detallesProducto['imagen']) ? $detallesProducto['imagen'] : '../uploads/default.jpg'; ?>" alt="<?= $detallesProducto['nombre']; ?>"></td>
                                <td><?= $detallesProducto['nombre']; ?></td>
                                <td class="precio-unitario" data-precio="<?= $precioUnitario; ?>">
                                    <?php if ($promocion): ?>
                                        <span class="price-normal">$<?= number_format($detallesProducto['precio'], 0, ',', '.'); ?></span>
                                        <span class="price-promo">$<?= number_format($precioUnitario, 0, ',', '.'); ?></span>
                                    <?php else: ?>
                                        <strong>$<?= number_format($precioUnitario, 0, ',', '.'); ?></strong>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <input type="number" class="form-control cantidad-producto" data-id="<?= $productoId; ?>" data-categoria="<?= $categoria; ?>" value="<?= $producto['cantidad']; ?>" min="1">
                                </td>
                                <td class="subtotal">$<?= number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <span class="icon-trash eliminar-producto" data-id="<?= $productoId; ?>" data-categoria="<?= $categoria; ?>">🗑</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total">
                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                        <td colspan="2" class="total-carrito fw-bold">$<?= number_format($totalCarrito, 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>

            <div class="d-flex justify-content-between">
                <button class="btn btn-danger btn-vaciar">Vaciar Carrito</button>
                <a href="../index/index-pago.php" class="btn btn-success btn-pagar">Ir a Pagar</a>
            </div>
        <?php else: ?>
            <p class="text-center">Tu carrito está vacío.</p>
        <?php endif; ?>
    </div>

    <script>
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
                        const precioUnitario = parseFloat(this.closest('tr').querySelector('.precio-unitario').dataset.precio);
                        const subtotal = precioUnitario * cantidad;
                        this.closest('tr').querySelector('.subtotal').textContent = `$${subtotal.toLocaleString('es-CL')}`;

                        let total = 0;
                        document.querySelectorAll('.subtotal').forEach(el => {
                            total += parseFloat(el.textContent.replace('$', '').replace(/\./g, ''));
                        });

                        document.querySelector('.total-carrito').textContent = `$${total.toLocaleString('es-CL')}`;

                        // Actualizar el contador del carrito en el header
                        const cartCountElement = document.getElementById('cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cartCount;
                        } else if (data.cartCount > 0) {
                            // Si no existe, agregar el contador al ícono del carrito
                            const cartIcon = document.querySelector('.bi-cart-fill');
                            const countSpan = document.createElement('span');
                            countSpan.id = 'cart-count';
                            countSpan.className = 'cart-count';
                            countSpan.textContent = data.cartCount;
                            cartIcon.parentElement.appendChild(countSpan);
                        }
                    } else {
                        alert('Error al actualizar la cantidad.');
                    }
                });
            });
        });


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
                        location.reload();
                    } else {
                        alert('Error al eliminar el producto.');
                    }
                });
            });
        });

        document.querySelector('.btn-vaciar').addEventListener('click', function () {
            fetch('../funciones/gestionar_carrito/vaciar_carrito.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Error al vaciar el carrito.');
                }
            });
        });
    </script>
</body>
</html>