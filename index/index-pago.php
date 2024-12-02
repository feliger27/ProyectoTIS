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

        // Asegúrate de que el índice 'precio' esté definido en el carrito
        $_SESSION['carrito'][$categoria][$productoId]['precio'] = $precioUnitario;

        // Sumar al total
        $totalCarrito += $producto['cantidad'] * $precioUnitario;
    }
}

// Calcular puntos generados por la compra al abrir la interfaz (5% del total)
$puntosGeneradosIniciales = floor($totalCarrito * 0.05);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin-top: 90px;
            background-color: #f8f9fa;
        }
        .card-header {
            background-color: #d35400; /* Naranja */
            color: white;
        }
        .btn-primary {
            background-color: #d35400; /* Naranja */
            border-color: #d35400; /* Naranja */
        }
        .btn-primary:hover {
            background-color: #b84400; /* Naranja más oscuro */
            border-color: #b84400; /* Naranja más oscuro */
        }
        h1 {
            color: #d35400;
            font-size: 35px;
        }
        .total-amount {
            font-size: 1.5rem;
            color: #d35400; /* Naranja */
            font-weight: bold;
        }
        .card {
            height: 100%;
        }
        .row > [class*="col"] {
            display: flex;
            flex-direction: column;
        }
        .main-container {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .gestionar-direcciones-btn {
            transition: transform 0.3s ease;
        }
        .gestionar-direcciones-btn:hover {
            transform: scale(1.1);
        }
        .metodo-pago-opcion {
            display: inline-block;
            width: 45%;
            text-align: center;
            cursor: pointer;
        }
        .metodo-pago-contenedor {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }
        .btn-check:checked + .metodo-pago-contenedor {
            border-color: #d35400; /* Naranja */
            background-color: #fad6b5; /* Naranja claro */
        }
        .icono-metodo {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }
        .metodo-pago-contenedor:hover {
            border-color: #d35400; /* Naranja */
            transform: scale(1.05);
        }
        .metodo-pago-contenedor p {
            margin: 0;
            font-weight: bold;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .text-muted {
            font-size: 0.9rem;
        }
        .text-danger {
            font-size: 0.9rem;
        }
        .list-unstyled li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
            text-align: center;
        }
        .d-flex span {
            display: inline-block;
            width: auto;
            white-space: nowrap;
        }
        .ms-4 {
            margin-left: 1rem;
        }
        .list-unstyled .subtotal-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .text-muted.text-decoration-line-through {
            margin-right: 5px;
        }
        .text-success.fw-bold {
            display: inline-block;
            text-align: center;
        }
        .puntos {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="main-container">
            <h1 class="text-center mb-4 fw-bold">Gestión de Compra</h1>

            <!-- Diseño Responsive -->
            <div class="row row-cols-1 row-cols-lg-2 g-4">
                <!-- Dirección -->
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="h5 mb-0">Dirección de Entrega</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Selecciona una dirección para recibir tu pedido:</label>
                                <select class="form-select" id="direccion" name="id_direccion" form="form-pago" required>
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
                            <div class="text-center">
                                <a href="index-perfil.php" class="btn btn-secondary gestionar-direcciones-btn">Gestionar Direcciones</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Puntos de Recompensa -->
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="h5 mb-0">Puntos de Recompensa</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="mb-0"><strong>Puntos Disponibles: <?= $puntosDisponibles; ?></strong></p>
                                <p class="text-muted mb-0" style="font-size: 0.9rem;">(1pto = $1)</p>
                            </div>
                            <label for="puntos_usados" class="form-label">Cantidad de puntos a utilizar:</label>
                            <input 
                                type="number" 
                                class="form-control text-center" 
                                id="puntos_usados" 
                                name="puntos_usados" 
                                form="form-pago"
                                min="0" 
                                max="<?= min($puntosDisponibles, $totalCarrito); ?>" 
                                value="0" 
                                oninput="actualizarTotal()">
                        </div>
                    </div>
                </div>

                <!-- Método de Pago -->
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="h5 mb-0">Método de Pago</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-around">
                                <!-- Opción Efectivo -->
                                <label class="metodo-pago-opcion" for="pago_efectivo">
                                    <input type="radio" class="btn-check" name="metodo_pago" id="pago_efectivo" form="form-pago" value="efectivo" required>
                                    <div class="metodo-pago-contenedor">
                                        <i class="bi bi-cash icono-metodo"></i>
                                        <p>Efectivo</p>
                                    </div>
                                </label>
                                <!-- Opción Transbank -->
                                <label class="metodo-pago-opcion" for="pago_transbank">
                                    <input type="radio" class="btn-check" name="metodo_pago" id="pago_transbank" form="form-pago" value="transbank">
                                    <div class="metodo-pago-contenedor">
                                        <i class="bi bi-credit-card icono-metodo"></i>
                                        <p>Transbank</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen del Pedido -->
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="h5 mb-0">Resumen del Pedido</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto">
                                    <h6>Productos:</h6>
                                    <ul class="list-unstyled">
                                        <?php foreach ($_SESSION['carrito'] as $categoria => $productos): ?>
                                            <?php foreach ($productos as $productoId => $producto): ?>
                                                <?php
                                                $queryProducto = "SELECT nombre_{$categoria} AS nombre, precio FROM {$categoria} WHERE id_{$categoria} = $productoId";
                                                $resultadoProducto = $conexion->query($queryProducto);
                                                $detallesProducto = $resultadoProducto->fetch_assoc();
                                                ?>
                                                <li>- <?= $detallesProducto['nombre']; ?> x<?= $producto['cantidad']; ?></li>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="col-auto ms-4">
                                    <h6 class="subtotal-title">Subtotal:</h6>
                                    <ul class="list-unstyled">
                                        <?php foreach ($_SESSION['carrito'] as $categoria => $productos): ?>
                                            <?php foreach ($productos as $productoId => $producto): ?>
                                                <?php
                                                $queryProducto = "SELECT precio FROM {$categoria} WHERE id_{$categoria} = $productoId";
                                                $resultadoProducto = $conexion->query($queryProducto);
                                                $detallesProducto = $resultadoProducto->fetch_assoc();

                                                $promocion = obtenerPromocion($productoId, $categoria, $promociones);
                                                $precioNormal = $detallesProducto['precio'] * $producto['cantidad'];
                                                $precioPromocional = $promocion ? calcularPrecioPromocional($detallesProducto['precio'], $promocion['porcentaje_descuento']) * $producto['cantidad'] : null;
                                                ?>
                                                <li>
                                                    <?php if ($promocion): ?>
                                                        <span class="text-muted text-decoration-line-through">$<?= number_format($precioNormal, 0, ',', '.'); ?></span>
                                                        <span class="text-success fw-bold">$<?= number_format($precioPromocional, 0, ',', '.'); ?></span>
                                                    <?php else: ?>
                                                        <span><strong>$<?= number_format($precioNormal, 0, ',', '.'); ?></strong></span>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <p>Por esta compra ganarás: <span class="puntos text-success fw-bold" id="puntos_generados"><?= $puntosGeneradosIniciales; ?> puntos</span></p>
                            </div>
                            <div class="text-center mt-3">
                                <p class="mb-0"><strong>Total:</strong> <span class="total-amount" id="total_compra">$<?= number_format($totalCarrito, 0, ',', '.'); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                                

            <div class="d-flex justify-content-between mt-4">
                <a href="index-menu.php" class="btn btn-secondary btn-lg">Volver al Menú</a>
                <form id="form-pago" action="../funciones/compra/procesar_pago.php" method="POST">
                    <input type="hidden" name="id_usuario" value="<?= $idUsuario; ?>">
                    <input type="hidden" name="total_compra" id="total_compra_hidden" value="<?= $totalCarrito; ?>">
                    <input type="hidden" name="puntos_usados" id="puntos_usados_hidden" value="0">
                    <button type="submit" class="btn btn-primary btn-lg">Confirmar Pedido</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function validarFormulario() {
            const metodoPago = document.querySelector('input[name="metodo_pago"]:checked');
            if (!metodoPago) {
                alert("Por favor, selecciona un método de pago.");
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function () {
            actualizarTotal();
        });

        function actualizarTotal() {
            const totalCarrito = <?= $totalCarrito; ?>;
            const puntosUsados = parseInt(document.getElementById('puntos_usados').value) || 0;
            const maxPuntos = <?= min($puntosDisponibles, $totalCarrito); ?>;

            if (puntosUsados > maxPuntos) {
                alert(`No puedes usar más de ${maxPuntos} puntos.`);
                document.getElementById('puntos_usados').value = maxPuntos;
                return;
            }

            const nuevoTotal = totalCarrito - puntosUsados;
            const totalFinal = nuevoTotal > 0 ? nuevoTotal : 0;

            const puntosGenerados = Math.max(Math.floor(totalFinal * 0.05), 0);

            document.getElementById('total_compra').textContent = `$${totalFinal.toLocaleString('es-CL')}`;
            document.getElementById('puntos_generados').textContent = `${puntosGenerados} puntos`;

        }


    </script>                    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>