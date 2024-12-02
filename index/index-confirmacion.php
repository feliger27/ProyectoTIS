<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../conexion.php');

// Verificar que se recibe el ID del pedido
if (!isset($_GET['id_pedido']) || !is_numeric($_GET['id_pedido'])) {
    die("Error: No se proporcionó un ID de pedido válido. ID recibido: " . ($_GET['id_pedido'] ?? 'No definido'));
}

$idPedido = (int)$_GET['id_pedido'];

// Recuperar los detalles del pedido
$queryPedido = "
    SELECT p.id_pedido, p.fecha_pedido, p.monto_total, p.estado_pedido, p.puntos_utilizados, d.calle, d.numero, d.ciudad, d.depto_oficina_piso
    FROM pedido p
    JOIN direccion d ON p.id_direccion = d.id_direccion
    WHERE p.id_pedido = ?
";
$stmtPedido = $conexion->prepare($queryPedido);
$stmtPedido->bind_param('i', $idPedido);
$stmtPedido->execute();
$resultadoPedido = $stmtPedido->get_result();

if ($resultadoPedido->num_rows === 0) {
    die("Error: No se encontraron detalles para el pedido especificado.");
}

$pedido = $resultadoPedido->fetch_assoc();
$stmtPedido->close();

// Calcular el monto con el descuento por puntos utilizados
$montoConDescuento = $pedido['monto_total'] - $pedido['puntos_utilizados'];

// Recuperar los artículos del pedido
$articulos = [];
$categorias = ['combo', 'hamburguesa', 'bebida', 'acompaniamiento', 'postre'];
foreach ($categorias as $categoria) {
    $query = "
        SELECT $categoria.nombre_$categoria AS nombre, pp.cantidad, pp.precio
        FROM pedido_{$categoria} pp
        JOIN $categoria $categoria ON pp.id_$categoria = $categoria.id_$categoria
        WHERE pp.id_pedido = $idPedido
    ";
    $resultado = $conexion->query($query);
    while ($item = $resultado->fetch_assoc()) {
        $articulos[] = [
            'categoria' => ucfirst($categoria),
            'nombre' => $item['nombre'],
            'cantidad' => $item['cantidad'],
            'precio' => $item['precio']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
        }
        .card-header {
            background-color: #2d3436;
            color: white;
            font-weight: bold;
        }
        .card-body {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .total-amount {
            font-size: 1.25rem;
            font-weight: bold;
            color: #e67e22;
        }
        .discount-amount {
            color: #e74c3c;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #0984e3;
            border: none;
        }
        .btn-primary:hover {
            background-color: #74b9ff;
        }
        table th, table td {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header text-center">
                <h2>Confirmación de Pedido</h2>
            </div>
            <div class="card-body">
                <!-- Detalles del pedido -->
                <div class="mb-4">
                    <h5>Detalles del Pedido</h5>
                    <p><strong>ID del Pedido:</strong> <?= $pedido['id_pedido']; ?></p>
                    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></p>
                    <p><strong>Estado:</strong> <?= ucfirst($pedido['estado_pedido']); ?></p>
                    <p><strong>Dirección:</strong> <?= $pedido['calle'] . ' #' . $pedido['numero'] . ', ' . $pedido['ciudad']; ?></p>
                    <?php if ($pedido['depto_oficina_piso']): ?>
                        <p><strong>Depto/Oficina/Piso:</strong> <?= $pedido['depto_oficina_piso']; ?></p>
                    <?php endif; ?>
                    <p><strong>Monto Total Original:</strong> <span class="total-amount">$<?= number_format($pedido['monto_total'], 0, ',', '.'); ?></span></p>
                    <?php if ($pedido['puntos_utilizados'] > 0): ?>
                        <p><strong>Puntos Utilizados:</strong> <?= $pedido['puntos_utilizados']; ?> puntos</p>
                        <p class="discount-amount"><strong>Descuento por Puntos:</strong> -$<?= number_format($pedido['puntos_utilizados'], 0, ',', '.'); ?></p>
                    <?php endif; ?>
                    <p><strong>Monto Total con Descuento:</strong> <span class="total-amount">$<?= number_format($montoConDescuento, 0, ',', '.'); ?></span></p>
                </div>

                <!-- Artículos del pedido -->
                <h5>Artículos del Pedido</h5>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articulos as $articulo): ?>
                            <tr>
                                <td><?= $articulo['categoria']; ?></td>
                                <td><?= $articulo['nombre']; ?></td>
                                <td><?= $articulo['cantidad']; ?></td>
                                <td>$<?= number_format($articulo['precio'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Botón para volver al inicio -->
                <div class="text-center mt-4">
                    <a href="../index/index-lobby.php" class="btn btn-primary">Volver al Inicio</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>