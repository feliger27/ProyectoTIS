<?php
include '../conexion.php';
session_start();
include '../includes/header.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

// Mostrar el mensaje de éxito si está configurado
if (isset($_SESSION['mensaje_exito'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='mensajeExito'>
            {$_SESSION['mensaje_exito']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    unset($_SESSION['mensaje_exito']);
}

$user_id = $_SESSION['user_id'];

// Consulta para obtener la información del usuario
$query_user = "SELECT nombre, apellido, correo_electronico, telefono FROM usuario WHERE id_usuario = ?";
$stmt_user = $conexion->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();
$stmt_user->close();

// Consulta para obtener direcciones del usuario actual
$query_direcciones = "SELECT d.id_direccion, d.calle, d.numero, d.ciudad, d.depto_oficina_piso
                      FROM direccion d
                      JOIN direccion_usuario du ON d.id_direccion = du.id_direccion
                      WHERE du.id_usuario = ?";
$stmt_direcciones = $conexion->prepare($query_direcciones);
$stmt_direcciones->bind_param("i", $user_id);
$stmt_direcciones->execute();
$result_direcciones = $stmt_direcciones->get_result();
$direcciones = $result_direcciones->fetch_all(MYSQLI_ASSOC);
$stmt_direcciones->close();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Mi Perfil - HamburGeeks</title>
    <style>
    .container .btn-primary, .container .btn-warning, .container .btn-danger, .container .btn-success {
    background-color: #fd7e14; 
    border-color: #fd7e14; 
    }

    .container .btn-primary:hover, .container .btn-warning:hover, .container .btn-danger:hover, .container .btn-success:hover {
    background-color: #e69500; 
    border-color: #e69500;
    }


    .container h2, .container h4, .container .nav-link.active {
    color: #fd7e14; /
    }

    .container .nav-link.active {
    background-color: #fd7e14;
    color: white;
    }
    .nav-pills .nav-link {
    color: black; 
    }

    /* Asegura que el contenido del cuerpo y html ocupe toda la altura */
    html, body {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Permite que el contenido ocupe el espacio disponible */
    body {
        flex-grow: 1;
    }

    /* Asegura que el footer siempre se quede en la parte inferior */
    footer {
        margin-top: auto; /* Empuja el footer al final si el contenido es corto */
        width: 100%; /* Asegura que ocupe todo el ancho de la pantalla */
    }
    </style>
</head>

<body>
    <div class="container pt-5 my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center">Mi Perfil</h2>
            <div>
                <a href="../login/logout.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>

        <!-- Barra de navegación en pestañas -->
        <ul class="nav nav-pills mb-4" id="perfil-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-tab" data-bs-toggle="pill" data-bs-target="#personal-info"
                    type="button" role="tab">Información Personal</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="addresses-tab" data-bs-toggle="pill" data-bs-target="#manage-addresses"
                    type="button" role="tab">Gestionar Direcciones</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="pill" data-bs-target="#settings"
                    type="button" role="tab">Configuración</button>
            </li>
        </ul>

        <!-- Contenido de las pestañas en tarjetas -->
        <div class="tab-content">
            <!-- Información Personal -->
            <div class="tab-pane fade show active" id="personal-info" role="tabpanel" aria-labelledby="personal-tab">
                <div class="card">
                    <div class="card-body">
                        <h4>Información Personal</h4>
                        <p><strong>Nombre:</strong> <?= htmlspecialchars($user_data['nombre']); ?></p>
                        <p><strong>Apellido:</strong> <?= htmlspecialchars($user_data['apellido']); ?></p>
                        <p><strong>Correo Electrónico:</strong>
                            <?= htmlspecialchars($user_data['correo_electronico']); ?></p>
                        <p><strong>Teléfono:</strong> <?= htmlspecialchars($user_data['telefono']); ?></p>
                        <button class="btn btn-primary mt-3" data-bs-toggle="modal"
                            data-bs-target="#editPersonalInfoModal">Editar</button>
                    </div>
                </div>
            </div>

            <!-- Modal para editar Información Personal -->
            <div class="modal fade" id="editPersonalInfoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Información Personal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../mantenedores/usuarios/editar.php?id=<?= $user_id ?>&origin=perfil" method="POST">
                                <div class="form-group mb-3">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($user_data['nombre']) ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="apellido">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= htmlspecialchars($user_data['apellido']) ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user_data['correo_electronico']) ?>" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="telefono">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($user_data['telefono']) ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Direcciones -->
            <div class="tab-pane fade" id="manage-addresses" role="tabpanel" aria-labelledby="addresses-tab">
                <div class="card">
                    <div class="card-body">
                        <h4>Gestionar Direcciones</h4>
                        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addAddressModal">Añadir Nueva Dirección</button>
                        <ul class="list-group">
                            <?php foreach ($direcciones as $direccion): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <?= htmlspecialchars($direccion['calle']) . ' ' . htmlspecialchars($direccion['numero']) . ', ' . htmlspecialchars($direccion['ciudad']). ' ' . htmlspecialchars($direccion['depto_oficina_piso']); ?>
                                    </span>
                                    <div>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAddressModal-<?= $direccion['id_direccion']; ?>">Editar</button>
                                        <a href="../funciones/gestionar_direcciones/eliminar_direccion.php?id_direccion=<?= $direccion['id_direccion']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta dirección?')">Eliminar</a>
                                    </div>
                                </li>

                                <!-- Modal para Editar Dirección -->
                                <div class="modal fade" id="editAddressModal-<?= $direccion['id_direccion']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Dirección</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../funciones/gestionar_direcciones/editar_direccion.php" method="POST">
                                                    <input type="hidden" name="id_direccion" value="<?= $direccion['id_direccion']; ?>">
                                                    <div class="form-group mb-3">
                                                        <label for="calle">Calle</label>
                                                        <input type="text" class="form-control" name="calle" value="<?= htmlspecialchars($direccion['calle']); ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="numero">Número</label>
                                                        <input type="text" class="form-control" name="numero" value="<?= htmlspecialchars($direccion['numero']); ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="ciudad">Ciudad</label>
                                                        <input type="text" class="form-control" name="ciudad" value="<?= htmlspecialchars($direccion['ciudad']); ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="depto_oficina_piso">Depto, Oficina, Piso</label>
                                                        <input type="text" class="form-control" name="depto_oficina_piso" value="<?= htmlspecialchars($direccion['depto_oficina_piso']); ?>" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Modal para Añadir Dirección -->
            <div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Añadir Nueva Dirección</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../funciones/gestionar_direcciones/insertar_direccion.php" method="POST">
                                <div class="form-group mb-3">
                                    <label for="calle">Calle</label>
                                    <input type="text" class="form-control" name="calle" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="numero">Número</label>
                                    <input type="text" class="form-control" name="numero" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="ciudad">Ciudad</label>
                                    <input type="text" class="form-control" name="ciudad" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="depto_oficina_piso">Depto, Oficina, Piso</label>
                                    <input type="text" class="form-control" name="depto_oficina_piso" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <div class="card">
                    <div class="card-body">
                        <h4>Mis Pedidos</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID Pedido</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Monto Total</th>
                                    <th>Valoración</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_pedidos = "
                                    SELECT id_pedido, estado_pedido, fecha_pedido, monto_total 
                                    FROM pedido 
                                    WHERE id_usuario = ? AND estado_pedido = 'entregado'";
                                $stmt_pedidos = $conexion->prepare($query_pedidos);
                                $stmt_pedidos->bind_param("i", $user_id);
                                $stmt_pedidos->execute();
                                $result_pedidos = $stmt_pedidos->get_result();

                                while ($pedido = $result_pedidos->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($pedido['id_pedido']) . "</td>";
                                    echo "<td>" . htmlspecialchars($pedido['estado_pedido']) . "</td>";
                                    echo "<td>" . htmlspecialchars($pedido['fecha_pedido']) . "</td>";
                                    echo "<td>" . htmlspecialchars($pedido['monto_total']) . "</td>";
                                    echo "<td><button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#valorarPedidoModal-{$pedido['id_pedido']}'>Valorar</button></td>";
                                    echo "</tr>";
                                }
                                $stmt_pedidos->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php
            $stmt_pedidos = $conexion->prepare("
                SELECT id_pedido 
                FROM pedido 
                WHERE id_usuario = ? AND estado_pedido = 'entregado'");
            $stmt_pedidos->bind_param("i", $user_id);
            $stmt_pedidos->execute();
            $result_pedidos = $stmt_pedidos->get_result();

            while ($pedido = $result_pedidos->fetch_assoc()) {
            ?>
            <div class="modal fade" id="valorarPedidoModal-<?= $pedido['id_pedido'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Valorar Pedido #<?= htmlspecialchars($pedido['id_pedido']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                        <form action="../funciones/gestionar_valoraciones/agregar_valoracion.php" method="POST">
                            <input type="hidden" name="id_pedido" value="<?= htmlspecialchars($pedido['id_pedido']); ?>">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">

                            <div class="form-group mb-3">
                                <label for="cantidad_estrellas">Calificación (1 a 5 estrellas)</label>
                                <select class="form-control" name="cantidad_estrellas" required>
                                    <option value="">Seleccione</option>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?= $i; ?>"><?= $i; ?> estrella<?= $i > 1 ? 's' : ''; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="comentario">Comentario</label>
                                <textarea class="form-control" name="comentario" rows="3" placeholder="Escribe un comentario (general)"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Enviar Valoración</button>
                        </form>


                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            $stmt_pedidos->close();
            ?>
        </div>
    </div>

    <script>
        setTimeout(function () {
            var mensajeExito = document.getElementById('mensajeExito');
            if (mensajeExito) {
                mensajeExito.classList.remove('show');
                mensajeExito.classList.add('fade');
            }
        }, 5000);
    </script>
    <script>
        document.getElementById('fecha_expiracion').addEventListener('input', function (e) {
            var input = e.target;
            var value = input.value.replace(/\D/g, ''); // Remover caracteres no numéricos
            var formattedValue = '';

            // Si se ingresan los primeros dos dígitos (MM)
            if (value.length > 0) {
                formattedValue = value.substring(0, 2);
                if (value.length >= 3) {
                    formattedValue += '/' + value.substring(2, 4); // Agregar '/' y los siguientes dos dígitos (YY)
                }
            }

            input.value = formattedValue; // Asignar el valor formateado al campo
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include '../includes/footer.php'; ?>