<?php
session_start();
include '../conexion.php';
include '../includes/header.php';

$user_id = $_SESSION['user_id']; // ID del usuario en sesión

// Obtener direcciones del usuario
$direcciones_query = $conexion->prepare("SELECT d.id_direccion, d.direccion, d.ciudad, d.codigo_postal 
                                         FROM direccion d 
                                         JOIN direccion_usuario du ON d.id_direccion = du.id_direccion 
                                         WHERE du.id_usuario = ?");
$direcciones_query->bind_param("i", $user_id);
$direcciones_query->execute();
$direcciones_result = $direcciones_query->get_result();
$direcciones = $direcciones_result->fetch_all(MYSQLI_ASSOC);

// Obtener métodos de pago
$metodos_pago_query = $conexion->query("SELECT * FROM metodo_pago");
$metodos_pago = $metodos_pago_query->fetch_all(MYSQLI_ASSOC);
?>

<div class="container my-5">
    <h1 class="text-center">Proceder al Pago</h1>

    <form action="procesar_pedido.php" method="POST">
        <!-- Selección de Dirección de Envío -->
        <h3 class="mt-4">Selecciona la Dirección de Envío</h3>
        <div class="form-group mb-3">
            <?php if (!empty($direcciones)): ?>
                <?php foreach ($direcciones as $direccion): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="direccion" id="direccion<?= $direccion['id_direccion'] ?>" value="<?= $direccion['id_direccion'] ?>" required>
                        <label class="form-check-label" for="direccion<?= $direccion['id_direccion'] ?>">
                            <?= $direccion['direccion'] ?>, <?= $direccion['ciudad'] ?>, <?= $direccion['codigo_postal'] ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No tienes direcciones registradas.</p>
            <?php endif; ?>

            <!-- Opción para agregar una nueva dirección -->
            <div class="form-check mt-3">
                <input class="form-check-input" type="radio" name="direccion" id="nueva_direccion" value="nueva">
                <label class="form-check-label" for="nueva_direccion">Usar una nueva dirección</label>
            </div>
            <div id="nuevaDireccionFields" style="display: none;">
                <input type="text" class="form-control my-2" name="direccion_nueva" placeholder="Dirección" required>
                <input type="text" class="form-control my-2" name="ciudad_nueva" placeholder="Ciudad" required>
                <input type="text" class="form-control my-2" name="codigo_postal_nuevo" placeholder="Código Postal" required>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="guardar_direccion" id="guardar_direccion">
                    <label class="form-check-label" for="guardar_direccion">Recordar esta dirección para futuros pedidos</label>
                </div>
            </div>
        </div>

        <!-- Selección de Método de Pago -->
        <h3 class="mt-4">Selecciona el Método de Pago</h3>
        <div class="form-group mb-4">
            <?php foreach ($metodos_pago as $metodo): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="metodo_pago" id="metodo<?= $metodo['id_metodo'] ?>" value="<?= $metodo['id_metodo'] ?>" required>
                    <label class="form-check-label" for="metodo<?= $metodo['id_metodo'] ?>">
                        <?= $metodo['nombre_metodo'] ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Botón para Confirmar Pedido -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Confirmar Pedido</button>
        </div>
    </form>
</div>

<script>
    // Mostrar campos de nueva dirección si se selecciona la opción "Usar una nueva dirección"
    document.getElementById('nueva_direccion').addEventListener('change', function () {
        document.getElementById('nuevaDireccionFields').style.display = this.checked ? 'block' : 'none';
    });
    
    // Ocultar campos si se selecciona una dirección existente
    document.querySelectorAll('input[name="direccion"]').forEach(function (el) {
        el.addEventListener('change', function () {
            if (el.id !== 'nueva_direccion') {
                document.getElementById('nuevaDireccionFields').style.display = 'none';
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
