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
    SELECT p.id_pedido, p.fecha_pedido, p.monto_total, p.estado_pedido, d.calle, d.numero, d.ciudad, d.depto_oficina_piso
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

// Recuperar los artículos del pedido
$articulos = [];

// Combos
$queryCombos = "
    SELECT c.nombre_combo AS nombre, pc.cantidad, pc.precio
    FROM pedido_combo pc
    JOIN combo c ON pc.id_combo = c.id_combo
    WHERE pc.id_pedido = $idPedido
";
$resultadoCombos = $conexion->query($queryCombos);
while ($combo = $resultadoCombos->fetch_assoc()) {
    $articulos[] = [
        'categoria' => 'Combo',
        'nombre' => $combo['nombre'],
        'cantidad' => $combo['cantidad'],
        'precio' => $combo['precio']
    ];
}

// Hamburguesas
$queryHamburguesas = "
    SELECT h.nombre_hamburguesa AS nombre, ph.cantidad, ph.precio
    FROM pedido_hamburguesa ph
    JOIN hamburguesa h ON ph.id_hamburguesa = h.id_hamburguesa
    WHERE ph.id_pedido = $idPedido
";
$resultadoHamburguesas = $conexion->query($queryHamburguesas);
while ($hamburguesa = $resultadoHamburguesas->fetch_assoc()) {
    $articulos[] = [
        'categoria' => 'Hamburguesa',
        'nombre' => $hamburguesa['nombre'],
        'cantidad' => $hamburguesa['cantidad'],
        'precio' => $hamburguesa['precio']
    ];
}

// Bebidas
$queryBebidas = "
    SELECT b.nombre_bebida AS nombre, pb.cantidad, pb.precio
    FROM pedido_bebida pb
    JOIN bebida b ON pb.id_bebida = b.id_bebida
    WHERE pb.id_pedido = $idPedido
";
$resultadoBebidas = $conexion->query($queryBebidas);
while ($bebida = $resultadoBebidas->fetch_assoc()) {
    $articulos[] = [
        'categoria' => 'Bebida',
        'nombre' => $bebida['nombre'],
        'cantidad' => $bebida['cantidad'],
        'precio' => $bebida['precio']
    ];
}

// Acompañamientos
$queryAcompanamientos = "
    SELECT a.nombre_acompaniamiento AS nombre, pa.cantidad, pa.precio
    FROM pedido_acompaniamiento pa
    JOIN acompaniamiento a ON pa.id_acompaniamiento = a.id_acompaniamiento
    WHERE pa.id_pedido = $idPedido
";
$resultadoAcompanamientos = $conexion->query($queryAcompanamientos);
while ($acompanamiento = $resultadoAcompanamientos->fetch_assoc()) {
    $articulos[] = [
        'categoria' => 'Acompañamiento',
        'nombre' => $acompanamiento['nombre'],
        'cantidad' => $acompanamiento['cantidad'],
        'precio' => $acompanamiento['precio']
    ];
}

// Postres
$queryPostres = "
    SELECT p.nombre_postre AS nombre, pp.cantidad, pp.precio
    FROM pedido_postre pp
    JOIN postre p ON pp.id_postre = p.id_postre
    WHERE pp.id_pedido = $idPedido
";
$resultadoPostres = $conexion->query($queryPostres);
while ($postre = $resultadoPostres->fetch_assoc()) {
    $articulos[] = [
        'categoria' => 'Postre',
        'nombre' => $postre['nombre'],
        'cantidad' => $postre['cantidad'],
        'precio' => $postre['precio']
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Confirmación de Pedido</h1>

        <!-- Detalles del pedido -->
        <h3>Detalles del Pedido</h3>
        <p><strong>ID del Pedido:</strong> <?= $pedido['id_pedido']; ?></p>
        <p><strong>Fecha:</strong> <?= $pedido['fecha_pedido']; ?></p>
        <p><strong>Estado:</strong> <?= ucfirst($pedido['estado_pedido']); ?></p>
        <p><strong>Dirección:</strong> <?= $pedido['calle'] . ' #' . $pedido['numero'] . ', ' . $pedido['ciudad']; ?></p>
        <?php if ($pedido['depto_oficina_piso']): ?>
            <p><strong>Depto/Oficina/Piso:</strong> <?= $pedido['depto_oficina_piso']; ?></p>
        <?php endif; ?>
        <p><strong>Monto Total:</strong> $<?= number_format($pedido['monto_total'], 0, ',', '.'); ?></p>

        <!-- Artículos del pedido -->
        <h3>Artículos del Pedido</h3>
        <table class="table">
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

        <a href="../index/index-lobby.php" class="btn btn-primary">Volver al Inicio</a>
    </div>
</body>
</html>