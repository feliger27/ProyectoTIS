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
$query_direcciones = "SELECT d.id_direccion, d.calle, d.numero, d.ciudad
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
            <form action="../funciones/procesamiento/procesar-pago.php" method="POST">
                <div class="mb-3">
                    <?php if ($direcciones && mysqli_num_rows($direcciones) > 0): ?>
                        <?php while($direccion = mysqli_fetch_assoc($direcciones)): ?>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="direccion_id" value="<?= $direccion['id_direccion'] ?>" required>
                                <label class="form-check-label"><?= $direccion['calle'] ?>, <?= $direccion['numero'] ?> - <?= $direccion['ciudad'] ?></label>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No se encontraron direcciones guardadas. Por favor, agrega una nueva dirección.</p>
                    <?php endif; ?>
                </div>

                <!-- Opción para agregar una nueva dirección -->
                <div class="mb-3">
                    <button type="button" class="btn btn-link" id="toggleNuevaDireccion">+ Agregar Nueva Dirección</button>
                </div>
                <div id="nuevaDireccionForm" style="display: none;">
                    <h4>Agregar Nueva Dirección</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nueva_calle" name="nueva_calle" placeholder="Calle" required />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nueva_numero" name="nueva_numero" placeholder="Número" required />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nueva_ciudad" name="nueva_ciudad" placeholder="Ciudad" required />
                        </div>
                    </div>
                    <button type="button" class="btn btn-success mt-3" id="guardarDireccion">Guardar Dirección</button>
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
                <div id="tarjetas-guardadas">
                    <h4>Seleccione una Tarjeta Guardada</h4>
                    <select id="tarjeta_guardada_select" class="form-select">
                        <option value="">Seleccione una tarjeta</option>
                        <?php while ($tarjeta = mysqli_fetch_assoc($tarjetas_guardadas)): ?>
                            <option 
                                value="<?= $tarjeta['id_pago'] ?>"
                                data-tipo="<?= $tarjeta['tipo_tarjeta'] ?>"
                                data-nombre="<?= $tarjeta['nombre_titular'] ?>"
                                data-numero="<?= $tarjeta['numero_tarjeta'] ?>"
                                data-fecha="<?= date('m/y', strtotime($tarjeta['fecha_expiracion'])) ?>">
                                <?= ucfirst($tarjeta['tipo_tarjeta']) ?> - **** **** **** <?= substr($tarjeta['numero_tarjeta'], -4) ?> - <?= $tarjeta['nombre_titular'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Botón para sistema de prueba de pago -->
                <a href="../funciones/transbank/crear_transaccion.php?monto=<?= $monto_total ?>" class="btn btn-secondary w-100 mt-2" id="sistemaPruebaPago">Ir al Sistema Prueba de Pago</a>
                <!-- Botón para confirmar pago -->
                <button type="submit" class="btn btn-primary w-100 mt-3">Confirmar y Pagar</button>
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
            const tarjetasGuardadas = document.getElementById('tarjetas-guardadas');
            const sistemaPruebaPago = document.getElementById('sistemaPruebaPago');

            if (this.value === 'efectivo') {
                sistemaPruebaPago.style.display = 'none';   // Oculta el botón de prueba
                tarjetaInfo.style.display = 'none';         // Oculta el formulario de tarjeta
                tarjetasGuardadas.style.display = 'none';   // Oculta las tarjetas guardadas
            } else {
                sistemaPruebaPago.style.display = 'block';  // Muestra el botón de prueba
                tarjetaInfo.style.display = 'block';        // Muestra el formulario de tarjeta
                tarjetasGuardadas.style.display = 'block';  // Muestra las tarjetas guardadas
            }
        });
    });

    // Guardar nueva dirección con AJAX
    document.getElementById('guardarDireccion').addEventListener('click', function() {
        const calle = document.getElementById('nueva_calle').value;
        const numero = document.getElementById('nueva_numero').value;
        const ciudad = document.getElementById('nueva_ciudad').value;

        if (!calle || !numero || !ciudad) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../funciones/procesamiento/insertar_direccion.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const direccionesContainer = document.querySelector('.mb-3');
                    const nuevaDireccionHTML = `
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="direccion_id" value="${response.id_direccion}" required>
                            <label class="form-check-label">${calle}, ${numero} - ${ciudad}</label>
                        </div>`;
                    direccionesContainer.insertAdjacentHTML('beforeend', nuevaDireccionHTML);
                    document.getElementById('nuevaDireccionForm').style.display = 'none';
                    alert("Dirección guardada exitosamente.");
                } else {
                    alert(response.error || "Error al guardar la dirección.");
                }
            } else {
                alert("Error en la solicitud. Inténtelo nuevamente.");
            }
        };
        xhr.send(`calle=${encodeURIComponent(calle)}&numero=${encodeURIComponent(numero)}&ciudad=${encodeURIComponent(ciudad)}`);
    });
</script>

<?php include('../includes/footer.php'); ?>
