<?php
include '../conexion.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

include('../includes/header.php');

$user_id = $_SESSION['user_id'];

// Obtener las direcciones guardadas del usuario
$query_direcciones = "SELECT d.id_direccion, d.calle, d.numero, d.ciudad, d.codigo_postal 
                      FROM direccion AS d
                      INNER JOIN direccion_usuario AS du ON d.id_direccion = du.id_direccion
                      WHERE du.id_usuario = $user_id";
$direcciones = mysqli_query($conexion, $query_direcciones);

// Obtener las tarjetas de débito guardadas del usuario
$query_debito = "SELECT mp.id_pago, mp.nombre_titular, mp.numero_tarjeta, mp.fecha_expiracion 
                 FROM metodo_pago AS mp
                 INNER JOIN usuario_metodo_pago AS ump ON mp.id_pago = ump.id_pago
                 WHERE ump.id_usuario = $user_id AND mp.tipo_tarjeta = 'Debito'";
$tarjetas_debito = mysqli_query($conexion, $query_debito);
?>

<div class="container my-5">
    <h2 class="text-center mb-4">Proceder al Pago</h2>
    
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">Seleccionar Dirección de Envío</h3>
            <form action="../funciones/procesamiento/procesar-pago.php" method="POST">
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

                <!-- Selección de tarjeta de débito guardada -->
                <?php if (mysqli_num_rows($tarjetas_debito) > 0): ?>
                    <div id="tarjetas-debito-guardadas" style="display: none;">
                        <h4>Seleccione una Tarjeta de Débito Guardada</h4>
                        <select name="tarjeta_debito_id" class="form-select">
                            <?php while($tarjeta = mysqli_fetch_assoc($tarjetas_debito)): ?>
                                <option value="<?= $tarjeta['id_pago'] ?>">
                                    <?= $tarjeta['nombre_titular'] ?> - **** **** **** <?= substr($tarjeta['numero_tarjeta'], -4) ?> (Exp: <?= date('m/y', strtotime($tarjeta['fecha_expiracion'])) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <!-- Información adicional para tarjeta -->
                <div id="tarjeta-info" style="display: none;">
                    <h4>Detalles de Tarjeta</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="nombre_titular" placeholder="Nombre del titular" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="numero_tarjeta" placeholder="Número de tarjeta" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="fecha_expiracion" placeholder="Fecha de expiración (MM/AA)" />
                        </div>
                        <div class="col-md-4" id="cuotas">
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

    // Cambiar formulario según método de pago
    document.querySelectorAll('input[name="metodo_pago"]').forEach((radio) => {
        radio.addEventListener('change', function() {
            const tarjetaInfo = document.getElementById('tarjeta-info');
            const cuotas = document.getElementById('cuotas');
            const tarjetasDebitoGuardadas = document.getElementById('tarjetas-debito-guardadas');
            
            if (this.value === 'debito') {
                tarjetaInfo.style.display = 'none';
                cuotas.style.display = 'none';
                tarjetasDebitoGuardadas.style.display = 'block';
            } else if (this.value === 'credito') {
                tarjetaInfo.style.display = 'block';
                cuotas.style.display = 'block';
                tarjetasDebitoGuardadas.style.display = 'none';
            } else {
                tarjetaInfo.style.display = 'none';
                tarjetasDebitoGuardadas.style.display = 'none';
            }
        });
    });
</script>

<?php include('../includes/footer.php'); ?>

