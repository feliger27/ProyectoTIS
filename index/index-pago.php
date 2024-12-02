<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir la conexión a la base de datos
include('../conexion.php');
include('../includes/header.php'); // Header con el contador del carrito

// Validar que el carrito no esté vacío
if (empty($_SESSION['carrito'])) {
    header('Location: index-carrito.php');
    exit;
}

// Cambiar de id_usuario a user_id
$idUsuario = $_SESSION['user_id'] ?? null; // Adaptado a la clave user_id
if (!$idUsuario) {
    header('Location: index-lobby.php'); // Redirigir si no hay usuario logueado
    exit;
}

// Obtener direcciones del usuario
$queryDirecciones = "
    SELECT d.id_direccion, d.calle, d.numero, d.ciudad, d.depto_oficina_piso 
    FROM direccion d
    JOIN direccion_usuario du ON d.id_direccion = du.id_direccion
    WHERE du.id_usuario = $idUsuario
";
$resultadoDirecciones = $conexion->query($queryDirecciones);

// Obtener puntos de recompensa del usuario
$queryPuntos = "SELECT puntos_recompensa FROM usuario WHERE id_usuario = $idUsuario";
$resultadoPuntos = $conexion->query($queryPuntos);
$puntosDisponibles = $resultadoPuntos->fetch_assoc()['puntos_recompensa'] ?? 0;

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

// Calcular el total del carrito
$totalCarrito = 0;
foreach ($_SESSION['carrito'] as $categoria => $productos) {
    foreach ($productos as $productoId => $producto) {
        // Obtener detalles del producto desde la base de datos
        $queryProducto = "SELECT nombre_{$categoria} AS nombre, precio FROM {$categoria} WHERE id_{$categoria} = $productoId";
        $resultadoProducto = $conexion->query($queryProducto);
        $detallesProducto = $resultadoProducto->fetch_assoc();

        // Calcular precio con promoción
        $promocion = obtenerPromocion($productoId, $categoria, $promociones);
        $precioUnitario = $promocion ? calcularPrecioPromocional($detallesProducto['precio'], $promocion['porcentaje_descuento']) : $detallesProducto['precio'];

        // Sumar al total
        $totalCarrito += $producto['cantidad'] * $precioUnitario;
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
    <script>
        // Función para mostrar u ocultar el botón de pago según el método seleccionado
        function togglePaymentButton() {
            const metodoPago = document.getElementById('metodo_pago').value;
            const btnConfirmar = document.getElementById('btn-confirmar-efectivo');
            const btnTransbank = document.getElementById('btn-transbank');

            if (metodoPago === 'efectivo') {
                btnConfirmar.style.display = 'block';
                btnTransbank.style.display = 'none';
            } else if (metodoPago === 'transbank') {
                btnConfirmar.style.display = 'none';
                btnTransbank.style.display = 'block';
            }
        }
    </script>
    <style>
        body {
            padding-top: 90px; /* Añade un espacio en la parte superior de la página */
        }
        .container {
            margin-top: 30px; /* Añade un espacio adicional solo al contenedor principal */
        }
        .header-section h1 {
            font-weight: bold;
            font-size: 2.5rem;
            color: #1a202c;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Confirmar Pedido</h1>

        <!-- Selección de Dirección -->
        <h3>Dirección de Entrega</h3>
        <form id="form-pago" action="../funciones/compra/procesar_pago.php" method="POST">
            <div class="mb-3">
                <label for="direccion" class="form-label">Selecciona una dirección:</label>
                <select class="form-select" id="direccion" name="id_direccion" required>
                    <?php if ($resultadoDirecciones->num_rows > 0): ?>
                        <?php while ($direccion = $resultadoDirecciones->fetch_assoc()): ?>
                            <option value="<?= $direccion['id_direccion']; ?>">
                                <?= $direccion['calle'] . ' #' . $direccion['numero'] . ', ' . $direccion['ciudad']; ?>
                                <?php if ($direccion['depto_oficina_piso']): ?>
                                    - <?= $direccion['depto_oficina_piso']; ?>
                                <?php endif; ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="" disabled>No tienes direcciones registradas.</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Puntos de recompensa -->
            <h3>Puntos de Recompensa</h3>
            <div class="mb-3">
                <p>Tienes <strong><?= $puntosDisponibles; ?></strong> puntos disponibles.</p>
                <label for="puntos_usados" class="form-label">Usar puntos (1 punto = $1):</label>
                <input type="number" class="form-control" id="puntos_usados" name="puntos_usados" max="<?= $puntosDisponibles; ?>" value="0">
            </div>

            <!-- Método de Pago -->
            <h3>Método de Pago</h3>
            <div class="mb-3">
                <label for="metodo_pago" class="form-label">Selecciona el método de pago:</label>
                <select class="form-select" id="metodo_pago" name="metodo_pago" onchange="togglePaymentButton()" required>
                    <option value="efectivo">Efectivo</option>
                    <option value="transbank">Transbank (próximamente)</option>
                </select>
            </div>

            <!-- Resumen del Pedido -->
            <h3>Resumen del Pedido</h3>
            <p><strong>Total:</strong> $<?= number_format($totalCarrito, 0, ',', '.'); ?></p>

            <!-- Campos ocultos -->
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($idUsuario, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="total_compra" value="<?= htmlspecialchars($totalCarrito, ENT_QUOTES, 'UTF-8'); ?>">

            <!-- Botón de Confirmar Pedido para efectivo -->
            <button type="submit" id="btn-confirmar-efectivo" class="btn btn-primary btn-lg" style="display: block;">Confirmar Pedido</button>

            <!-- Botón de Pago Transbank (deshabilitado por ahora) -->
            <button type="button" id="btn-transbank" class="btn btn-secondary btn-lg" style="display: none;" disabled>Pagar con Transbank</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>