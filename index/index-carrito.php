<?php
session_start();
include('../conexion.php');
include('../includes/header.php');

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
function calcularPrecioPromocional($precio, $descuento)
{
    return $precio - ($precio * $descuento / 100);
}

// Función para buscar una promoción activa para un producto
function obtenerPromocion($productoId, $categoria, $promociones)
{
    foreach ($promociones as $promo) {
        if (
            ($categoria === 'hamburguesa' && isset($promo['id_hamburguesa']) && $promo['id_hamburguesa'] == $productoId) ||
            ($categoria === 'bebida' && isset($promo['id_bebida']) && $promo['id_bebida'] == $productoId) ||
            ($categoria === 'acompaniamiento' && isset($promo['id_acompaniamiento']) && $promo['id_acompaniamiento'] == $productoId) ||
            ($categoria === 'postre' && isset($promo['id_postre']) && $promo['id_postre'] == $productoId) ||
            ($categoria === 'combo' && isset($promo['id_combo']) && $promo['id_combo'] == $productoId)
        ) {
            return $promo;
        }
    }
    return null;
}

// Verificar si el carrito existe
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            color: #333;
            font-family: Arial, sans-serif;
        }

        .carrito-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .carrito-header {
            margin-top: 20px;
            text-align: center;
            margin-bottom: 30px;
        }

        .carrito-header h1 {
            font-size: 2.5rem;
            color: #d35400;
            font-weight: bold;
        }

        .producto-imagen {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table th {
            background-color: #d35400;
            color: white;
        }

        .btn-vaciar {
            background-color: #e74c3c;
            color: white;
            font-weight: bold;
        }

        .btn-vaciar:hover {
            background-color: #c0392b;
        }

        .btn-seguir {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
        }

        .btn-seguir:hover {
            background-color: #229954;
        }

        .icono-basura {
            color: #e74c3c;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .icono-basura:hover {
            color: #c0392b;
        }

        .total-container {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .precio-normal {
            font-size: 1rem;
            color: #7f8c8d;
            /* Gris */
            text-decoration: line-through;
            margin-right: 5px;
        }

        .precio-promocional {
            font-size: 1.1rem;
            /* Más grande */
            color: #27ae60;
            /* Verde */
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container carrito-container">
        <div class="carrito-header">
            <h1>Mi Carrito</h1>
        </div>
        <?php if (!empty($carrito)): ?>
            <table class="table table-bordered">
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
                    <?php
                    $total = 0;
                    foreach ($carrito as $categoria => $productos):
                        foreach ($productos as $id => $producto):
                            // Verificar si el producto tiene una promoción activa
                            $promocion = obtenerPromocion($id, $categoria, $promociones);
                            $precioUnitario = $promocion ? calcularPrecioPromocional($producto['precio'], $promocion['porcentaje_descuento']) : $producto['precio'];
                            $subtotal = $precioUnitario * $producto['cantidad'];
                            $total += $subtotal;
                            ?>
                            <tr id="producto-<?php echo $id; ?>">
                                <td>
                                    <img src="<?php echo $producto['imagen']; ?>" class="producto-imagen"
                                        alt="<?php echo $producto['nombre']; ?>">
                                </td>
                                <td><?php echo $producto['nombre']; ?></td>
                                <td>
                                    <?php if ($promocion): ?>
                                        <span
                                            class="precio-normal">$<?php echo number_format($producto['precio'], 0, ',', '.'); ?></span>
                                        <span
                                            class="precio-promocional">$<?php echo number_format($precioUnitario, 0, ',', '.'); ?></span>
                                    <?php else: ?>
                                        $<?php echo number_format($producto['precio'], 0, ',', '.'); ?>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <input type="number" name="cantidad" value="<?php echo $producto['cantidad']; ?>" min="1"
                                        class="form-control text-center"
                                        onchange="actualizarCantidad('<?php echo $id; ?>', '<?php echo $categoria; ?>', this.value)">
                                </td>
                                <td id="subtotal-<?php echo $id; ?>">$<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <i class="fas fa-trash icono-basura"
                                        onclick="eliminarProducto('<?php echo $id; ?>', '<?php echo $categoria; ?>')"></i>
                                </td>
                            </tr>
                        <?php endforeach; endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end total-container">Total</td>
                        <td colspan="2" class="total-container" id="total">
                            $<?php echo number_format($total, 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
            <div class="d-flex justify-content-between">
                <button class="btn btn-vaciar" onclick="vaciarCarrito()">Vaciar Carrito</button>
                <a href="../index/index-menu.php" class="btn btn-seguir">Seguir Comprando</a>
            </div>
        <?php else: ?>
            <p class="text-center">Tu carrito está vacío. <a href="../index/index-menu.php">Explorar menú</a></p>
        <?php endif; ?>
    </div>

    <script>
        function actualizarCantidad(idProducto, categoria, cantidad) {
            if (cantidad < 1) {
                alert('La cantidad debe ser al menos 1.');
                return;
            }

            fetch('../funciones/gestionar_carrito/actualizar_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    idProducto: idProducto,
                    categoria: categoria,
                    cantidad: cantidad
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error('Error del servidor:', data.error);
                        alert(data.error);
                    } else {
                        // Actualizar los valores en la interfaz
                        const subtotalElem = document.getElementById(`subtotal-${idProducto}`);
                        const totalElem = document.getElementById('total');

                        // Actualiza el subtotal del producto
                        if (subtotalElem) {
                            subtotalElem.innerText = data.subtotal;
                        } else {
                            console.error(`Elemento con ID subtotal-${idProducto} no encontrado.`);
                        }

                        // Actualiza el total del carrito
                        if (totalElem) {
                            totalElem.innerText = data.total;
                        } else {
                            console.error('Elemento con ID total no encontrado.');
                        }
                    }
                })
                .catch(error => console.error('Error en la solicitud AJAX:', error));
        }





        function eliminarProducto(idProducto, categoria) {
            fetch('../funciones/gestionar_carrito/eliminar_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ idProducto, categoria })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        location.reload();
                    }
                })
                .catch(err => console.error(err));
        }

        function vaciarCarrito() {
            fetch('../funciones/gestionar_carrito/vaciar_carrito.php', {
                method: 'POST'
            })
                .then(response => location.reload())
                .catch(err => console.error(err));
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>