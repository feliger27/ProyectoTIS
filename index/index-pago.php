<?php
include '../conexion.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

include('../includes/header.php');

$user_id = $_SESSION['user_id'];
$monto_total = isset($_SESSION['total_carrito']) ? $_SESSION['total_carrito'] : 0;

// Crear un nuevo pedido en la base de datos
$stmt = $conexion->prepare("INSERT INTO pedido (id_usuario, total, fecha_pedido) VALUES (?, ?, NOW())");
$stmt->bind_param("ii", $user_id, $monto_total);
$stmt->execute();
$id_pedido = $stmt->insert_id;
$stmt->close();

// Guardar el ID del pedido en la sesión
$_SESSION['id_pedido'] = $id_pedido;

// Obtener direcciones del usuario
$query_direcciones = "SELECT d.id_direccion, d.calle, d.numero, d.ciudad
                      FROM direccion AS d
                      INNER JOIN direccion_usuario AS du ON d.id_direccion = du.id_direccion
                      WHERE du.id_usuario = $user_id";
$direcciones = mysqli_query($conexion, $query_direcciones);

// Obtener tarjetas guardadas del usuario
$query_tarjetas = "SELECT mp.id_pago, mp.nombre_titular, mp.numero_tarjeta, mp.fecha_expiracion, mp.tipo_tarjeta
                   FROM metodo_pago AS mp
                   INNER JOIN usuario_metodo_pago AS ump ON mp.id_pago = ump.id_pago
                   WHERE ump.id_usuario = $user_id";
$tarjetas_guardadas = mysqli_query($conexion, $query_tarjetas);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceder al Pago - HamburGeeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content {
            flex: 1;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container my-5 content">
        <!-- Título con mayor espaciado superior -->
        <h1 class="text-center mb-4 pt-5" style="color: #D35400;">Proceder al Pago</h1>

        <div class="card shadow-lg mb-5">
            <div class="card-body">
                <form action="../funciones/procesamiento/procesar-pago.php" method="POST">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="background-color: #D35400; color: white;">Dirección de Envío</th>
                                <th class="text-center" style="background-color: #D35400; color: white;">Método de Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <!-- Columna de Dirección -->
                                <td>
                                    <?php if ($direcciones && mysqli_num_rows($direcciones) > 0): ?>
                                        <?php while ($direccion = mysqli_fetch_assoc($direcciones)): ?>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="direccion_id" value="<?= $direccion['id_direccion'] ?>" required>
                                                <label class="form-check-label text-dark"><?= $direccion['calle'] ?>, <?= $direccion['numero'] ?> - <?= $direccion['ciudad'] ?></label>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p class="text-dark">No tienes direcciones guardadas. Por favor, agrega una nueva dirección.</p>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline-secondary mt-3" id="toggleNuevaDireccion">+ Agregar Nueva Dirección</button>
                                    <div id="nuevaDireccionForm" class="mt-3 d-none">
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <input type="text" class="form-control" id="nueva_calle" name="nueva_calle" placeholder="Calle" required>
                                            </div>
                                            <div class="col-4">
                                                <input type="text" class="form-control" id="nueva_numero" name="nueva_numero" placeholder="Número" required>
                                            </div>
                                            <div class="col-4">
                                                <input type="text" class="form-control" id="nueva_ciudad" name="nueva_ciudad" placeholder="Ciudad" required>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success mt-3 w-100" id="guardarDireccion">Guardar Dirección</button>
                                    </div>
                                </td>

                                <!-- Columna de Método de Pago -->
                                <td>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="metodo_pago" value="efectivo" required>
                                        <label class="form-check-label text-dark">Efectivo</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="metodo_pago" value="debito">
                                        <label class="form-check-label text-dark">Tarjeta de Débito</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="metodo_pago" value="credito">
                                        <label class="form-check-label text-dark">Tarjeta de Crédito</label>
                                    </div>
                                    <div id="tarjetas-guardadas" class="mt-3">
                                        <h5 class="text-secondary">Tarjetas Guardadas</h5>
                                        <select id="tarjeta_guardada_select" class="form-select">
                                            <option value="">Seleccione una tarjeta</option>
                                            <?php while ($tarjeta = mysqli_fetch_assoc($tarjetas_guardadas)): ?>
                                                <option 
                                                    value="<?= $tarjeta['id_pago'] ?>"><?= ucfirst($tarjeta['tipo_tarjeta']) ?> - **** <?= substr($tarjeta['numero_tarjeta'], -4) ?> - <?= $tarjeta['nombre_titular'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Total y Botones centrados -->
                    <div class="mt-4 text-center">
                        <h4 class="text-dark mb-3">Total: <span class="text-warning">$<?= number_format($monto_total, 0, ',', '.') ?></span></h4>
                        <div class="d-inline-flex gap-2">
                            <a href="../funciones/transbank/crear_transaccion.php?monto=<?= $monto_total ?>" class="btn btn-outline-secondary">Ir al Sistema de Pago</a>
                            <button type="submit" class="btn text-white" style="background-color: #D35400;">Confirmar y Pagar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer fijo al final -->
    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">© 2024 HamburGeeks. Todos los derechos reservados.</p>
    </footer>

    <script>
        document.getElementById('toggleNuevaDireccion').addEventListener('click', function () {
            const nuevaDireccionForm = document.getElementById('nuevaDireccionForm');
            nuevaDireccionForm.classList.toggle('d-none');
        });
    </script>

    <?php include('../includes/footer.php'); ?>
</body>

</html>
