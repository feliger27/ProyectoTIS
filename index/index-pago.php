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
$id_pedido = $stmt->insert_id; // Recuperar el ID del pedido generado
$stmt->close();

// Guardar el ID del pedido en la sesión para utilizarlo en los siguientes pasos
$_SESSION['id_pedido'] = $id_pedido;

// Obtener las direcciones guardadas del usuario
$query_direcciones = "SELECT d.id_direccion, d.calle, d.numero, d.ciudad, d.codigo_postal 
                      FROM direccion AS d
                      INNER JOIN direccion_usuario AS du ON d.id_direccion = du.id_direccion
                      WHERE du.id_usuario = $user_id";
$direcciones = mysqli_query($conexion, $query_direcciones);

// Obtener todas las tarjetas guardadas del usuario
$query_tarjetas = "SELECT mp.id_pago, mp.nombre_titular, mp.numero_tarjeta, mp.fecha_expiracion, mp.tipo_tarjeta
                   FROM metodo_pago AS mp
                   INNER JOIN usuario_metodo_pago AS ump ON mp.id_pago = ump.id_pago
                   WHERE ump.id_usuario = $user_id";
$tarjetas_guardadas = mysqli_query($conexion, $query_tarjetas);
?>

<div class="container my-5">
    <h2 class="text-center mb-4">Proceder al Pago</h2>
    
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">Seleccionar Dirección de Envío</h3>
            <form action="../transbank/crear_transaccion.php" method="POST">
                <div class="mb-3">
                    <?php while($direccion = mysqli_fetch_assoc($direcciones)): ?>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="direccion_id" value="<?= $direccion['id_direccion'] ?>" required>
                            <label class="form-check-label">
                                <?= $direccion['calle'] ?>, <?= $direccion['numero'] ?>, <?= $direccion['ciudad'] ?>, <?= $direccion['codigo_postal'] ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Opción para agregar una nueva dirección -->
                <div class="mb-3">
                    <button type="button" class="btn btn-link" id="toggleNuevaDireccion">+ Agregar Nueva Dirección</button>
                </div>
                <div id="nuevaDireccionForm" style="display: none;">
                    <h4>Agregar Nueva Dirección</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="nueva_calle" placeholder="Calle" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="nueva_ciudad" placeholder="Ciudad" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="nuevo_codigo_postal" placeholder="Código Postal" />
                        </div>
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" name="recordar_direccion" value="1">
                        <label class="form-check-label">Recordar esta dirección para futuros pedidos</label>
                    </div>
                </div>

                <h4 class="mt-4">Seleccionar Método de Pago</h4>
                <div class="mb-3">
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="metodo_pago" value="efectivo" required>
                        <label class="form-check-label">Efectivo</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="metodo_pago" value="debito">
                        <label class="form-check-label">Tarjeta de Débito</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="metodo_pago" value="credito">
                        <label class="form-check-label">Tarjeta de Crédito</label>
                    </div>
                </div>

                <!-- Selección de tarjeta guardada -->
                <div id="tarjetas-guardadas" style="display: none;">
                    <h4>Seleccione una Tarjeta Guardada</h4>
                    <select id="tarjeta_guardada_select" class="form-select">
                        <option value="">Seleccione una tarjeta</option>
                        <?php while($tarjeta = mysqli_fetch_assoc($tarjetas_guardadas)): ?>
                            <option value="<?= $tarjeta['id_pago'] ?>"
                                    data-tipo="<?= $tarjeta['tipo_tarjeta'] ?>"
                                    data-nombre="<?= $tarjeta['nombre_titular'] ?>"
                                    data-numero="<?= $tarjeta['numero_tarjeta'] ?>"
                                    data-fecha="<?= date('m/y', strtotime($tarjeta['fecha_expiracion'])) ?>">
                                <?= $tarjeta['nombre_titular'] ?> - **** **** **** <?= substr($tarjeta['numero_tarjeta'], -4) ?> (<?= ucfirst($tarjeta['tipo_tarjeta']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Información adicional para tarjeta -->
                <div id="tarjeta-info" style="display: none;">
                    <h4>Detalles de Tarjeta</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nombre_titular" name="nombre_titular" placeholder="Nombre del titular" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" placeholder="Número de tarjeta" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="fecha_expiracion" name="fecha_expiracion" placeholder="Fecha de expiración (MM/AA)" />
                        </div>
                        <div class="col-md-4" id="cuotas" style="display: none;">
                            <label for="num_cuotas">Cuotas:</label>
                            <select name="num_cuotas" class="form-select">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?> cuota<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input" name="recordar_metodo_pago" value="1">
                        <label class="form-check-label">Recordar este método de pago</label>
                    </div>
                </div>

                <!-- Botón para enviar el formulario -->
                <input type="hidden" name="monto" value="<?= $monto_total ?>"> <!-- Usar el monto del carrito -->
                <input type="hidden" name="pedido_id" value="<?= $id_pedido ?>"> <!-- Incluir el ID del pedido -->
                <button type="submit" class="btn btn-primary w-100">Confirmar y Pagar</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Mostrar/Ocultar el formulario de nueva dirección
    document.getElementById('toggleNuevaDireccion').addEventListener('click', function() {
        const nuevaDireccionForm = document.getElementById('nuevaDireccionForm');
        nuevaDireccionForm.style.display = nuevaDireccionForm.style.display === 'none' ? 'block' : 'none';
    });

    // Cambiar formulario según método de pago y mostrar tarjetas guardadas
    document.querySelectorAll('input[name="metodo_pago"]').forEach((radio) => {
        radio.addEventListener('change', function() {
            const tarjetaInfo = document.getElementById('tarjeta-info');
            const cuotas = document.getElementById('cuotas');
            const tarjetasGuardadas = document.getElementById('tarjetas-guardadas');
            const tarjetaSelect = document.getElementById('tarjeta_guardada_select');

            // Mostrar el formulario y lista desplegable de tarjetas según el tipo seleccionado
            tarjetaInfo.style.display = 'block';
            tarjetasGuardadas.style.display = 'block';

            // Filtrar tarjetas guardadas según el tipo de tarjeta
            for (let i = 1; i < tarjetaSelect.options.length; i++) {
                const tipoTarjeta = tarjetaSelect.options[i].getAttribute('data-tipo');
                tarjetaSelect.options[i].style.display = (this.value === tipoTarjeta) ? 'block' : 'none';
            }

            // Mostrar u ocultar el campo de cuotas solo para crédito
            cuotas.style.display = (this.value === 'credito') ? 'block' : 'none';
        });
    });

    // Autocompletar campos al seleccionar una tarjeta guardada
    document.getElementById('tarjeta_guardada_select').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('nombre_titular').value = selectedOption.getAttribute('data-nombre') || '';
        document.getElementById('numero_tarjeta').value = selectedOption.getAttribute('data-numero') || '';
        document.getElementById('fecha_expiracion').value = selectedOption.getAttribute('data-fecha') || '';
    });
</script>

<?php include('../includes/footer.php'); ?>
