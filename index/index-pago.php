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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            background-color: #ff4500;
            color: white;
        }
        .btn-primary {
            background-color: #ff4500;
            border-color: #ff4500;
        }
        .btn-primary:hover {
            background-color: #e03e00;
            border-color: #e03e00;
        }
        h1 {
            color: #333;
        }
        .total-amount {
            font-size: 1.5rem;
            color: #ff4500;
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
        width: 45%; /* Asegura que ambas opciones ocupen la mitad del espacio */
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
        border-color: #ff4500;
        background-color: #ffe6e1;
    }

    .icono-metodo {
        max-width: 50px;
        margin-bottom: 10px;
    }

    .metodo-pago-contenedor:hover {
        border-color: #ff4500;
        transform: scale(1.05);
    }

    .metodo-pago-contenedor p {
        margin: 0;
        font-weight: bold;
    }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="main-container">
            <h1 class="text-center mb-4">Confirmar Pedido</h1>

            <!-- Diseño Responsive -->
            <div class="row row-cols-1 row-cols-lg-2 g-4">
                <!-- Dirección -->
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="h5 mb-0">Dirección de Entrega</h3>
                        </div>
                        <div class="card-body">
                            <form id="form-pago" action="../funciones/compra/procesar_pago.php" method="POST" onsubmit="return validarFormulario();">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Selecciona una dirección para recibir tu pedido:</label>
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
                                <div class="text-center">
                                    <a href="index-perfil.php" class="btn btn-secondary gestionar-direcciones-btn">Gestionar Direcciones</a>
                                </div>
                            </form>
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
                                min="0" 
                                max="<?= min($puntosDisponibles, $totalCarrito); ?>" 
                                value="0" 
                                oninput="actualizarTotal(); validarPuntos();">
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
                                    <input type="radio" class="btn-check" name="metodo_pago" id="pago_efectivo" value="efectivo" required>
                                    <div class="metodo-pago-contenedor">
                                        <img src="https://img.icons8.com/ios-filled/50/000000/cash.png" alt="Efectivo" class="icono-metodo">
                                        <p>Efectivo</p>
                                    </div>
                                </label>
                                <!-- Opción Transbank -->
                                <label class="metodo-pago-opcion" for="pago_transbank">
                                    <input type="radio" class="btn-check" name="metodo_pago" id="pago_transbank" value="transbank">
                                    <div class="metodo-pago-contenedor">
                                        <img src="https://img.icons8.com/ios-filled/50/000000/bank-card-front-side.png" alt="Transbank" class="icono-metodo">
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
                        <div class="card-body text-center">
                            <p>Total: <span class="total-amount">$<?= number_format($totalCarrito, 0, ',', '.'); ?></span></p>
                            <input type="hidden" id="totalCompra" value="<?= htmlspecialchars($totalCarrito, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($idUsuario, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="total_compra" id="total_compra" value="<?= htmlspecialchars($totalCarrito, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón de Confirmar Pedido -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Confirmar Pedido</button>
            </div>
        </div>
    </div>
    <script>
        // Validar que un método de pago esté seleccionado antes de enviar
        function validarFormulario() {
            const metodoPago = document.querySelector('input[name="metodo_pago"]:checked');
            if (!metodoPago) {
                alert("Por favor, selecciona un método de pago.");
                return false;
            }
            return true;
        }

        // Función para actualizar el precio total dinámicamente
        function actualizarTotal() {
            const totalCompra = parseFloat(document.getElementById('totalCompra').value); // Total original
            const puntosUsados = parseInt(document.getElementById('puntos_usados').value) || 0; // Puntos ingresados
            const maxPuntos = parseInt(document.getElementById('puntos_usados').max); // Máximo de puntos disponibles

            // Validar que los puntos no superen el máximo
            if (puntosUsados > maxPuntos) {
                alert("No puedes usar más puntos de los disponibles.");
                document.getElementById('puntos_usados').value = maxPuntos;
                return;
            }

            // Calcular el nuevo total
            const nuevoTotal = totalCompra - puntosUsados;

            // Mostrar el nuevo total (asegurándonos de que no sea negativo)
            document.getElementById('totalConDescuento').textContent = 
                nuevoTotal > 0 ? nuevoTotal.toLocaleString() : '0';
            document.getElementById('total_compra').value = nuevoTotal > 0 ? nuevoTotal : 0; // Actualizar el input oculto

            function validarPuntos() {
                const puntosUsados = parseInt(document.getElementById('puntos_usados').value) || 0; // Puntos ingresados
                const maxPuntos = parseInt(document.getElementById('puntos_usados').max); // Máximo permitido
                const minPuntos = parseInt(document.getElementById('puntos_usados').min); // Mínimo permitido

                // Verificar si los puntos están fuera del rango permitido
                if (puntosUsados < minPuntos) {
                    alert("No puedes usar un valor negativo.");
                    document.getElementById('puntos_usados').value = minPuntos;
                } else if (puntosUsados > maxPuntos) {
                    alert(`No puedes usar más de ${maxPuntos} puntos.`);
                    document.getElementById('puntos_usados').value = maxPuntos;
                }
            }
            
            function actualizarLimites() {
                const totalCompra = parseFloat(document.getElementById('totalCompra').value); // Total del carrito
                const maxPuntosDisponibles = parseInt(document.getElementById('puntos_usados').max); // Puntos disponibles

                // Ajustar el máximo dinámicamente
                const nuevoMaximo = Math.min(maxPuntosDisponibles, totalCompra);
                document.getElementById('puntos_usados').max = nuevoMaximo;
            }
        }
    </script>                    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>