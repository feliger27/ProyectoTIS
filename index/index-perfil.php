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
$query_direcciones = "SELECT d.id_direccion, d.calle, d.numero, d.ciudad
                      FROM direccion d
                      JOIN direccion_usuario du ON d.id_direccion = du.id_direccion
                      WHERE du.id_usuario = ?";
$stmt_direcciones = $conexion->prepare($query_direcciones);
$stmt_direcciones->bind_param("i", $user_id);
$stmt_direcciones->execute();
$result_direcciones = $stmt_direcciones->get_result();
$direcciones = $result_direcciones->fetch_all(MYSQLI_ASSOC);
$stmt_direcciones->close();

// Consulta para obtener métodos de pago del usuario actual
$query_metodos_pago = "SELECT mp.id_pago, mp.tipo_tarjeta, mp.numero_tarjeta, mp.fecha_expiracion, mp.nombre_titular 
                       FROM metodo_pago mp
                       JOIN usuario_metodo_pago ump ON mp.id_pago = ump.id_pago
                       WHERE ump.id_usuario = ?";
$stmt_metodos_pago = $conexion->prepare($query_metodos_pago);
$stmt_metodos_pago->bind_param("i", $user_id);
$stmt_metodos_pago->execute();
$result_metodos_pago = $stmt_metodos_pago->get_result();
$metodos_pago = $result_metodos_pago->fetch_all(MYSQLI_ASSOC);
$stmt_metodos_pago->close();
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
        color: #fd7e14; 
    }

    .container .nav-link.active {
        background-color: #fd7e14;
        color: white;
    }

    .nav-pills .nav-link {
        color: black; 
    }

    html, body {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    body {
        flex-grow: 1;
    }

    footer {
        margin-top: auto; 
        width: 100%;
    }
    </style>
</head>

<body>
    <div class="container pt-4 my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center">Mi Perfil</h2>
            <div>
                <a href="../login/logout.php" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>

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
                <button class="nav-link" id="orders-tab" data-bs-toggle="pill" data-bs-target="#manage-orders"
                    type="button" role="tab">Pedidos</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="personal-info" role="tabpanel" aria-labelledby="personal-tab">
                <div class="card">
                    <div class="card-body">
                        <h4>Información Personal</h4>
                        <p><strong>Nombre:</strong> <?= htmlspecialchars($user_data['nombre']); ?></p>
                        <p><strong>Apellido:</strong> <?= htmlspecialchars($user_data['apellido']); ?></p>
                        <p><strong>Correo Electrónico:</strong> <?= htmlspecialchars($user_data['correo_electronico']); ?></p>
                        <p><strong>Teléfono:</strong> <?= htmlspecialchars($user_data['telefono']); ?></p>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="manage-orders" role="tabpanel" aria-labelledby="orders-tab">
                <div class="card">
                    <div class="card-body">
                        <h4>Mis Pedidos</h4>
                        <div class="list-group">
                            <?php
                            $query_pedidos = "SELECT p.id_pedido, p.fecha_pedido, p.estado_pedido, p.monto, d.calle, d.numero, d.ciudad
                                              FROM pedido p
                                              LEFT JOIN direccion d ON p.id_direccion = d.id_direccion
                                              WHERE p.id_usuario = ?";
                            $stmt_pedidos = $conexion->prepare($query_pedidos);
                            $stmt_pedidos->bind_param("i", $user_id);
                            $stmt_pedidos->execute();
                            $result_pedidos = $stmt_pedidos->get_result();

                            if ($result_pedidos->num_rows > 0):
                                while ($pedido = $result_pedidos->fetch_assoc()):
                            ?>
                            <div class="list-group-item">
                                <h5 class="mb-1">Pedido #<?= htmlspecialchars($pedido['id_pedido']); ?></h5>
                                <p class="mb-1"><strong>Fecha:</strong> <?= htmlspecialchars($pedido['fecha_pedido']); ?></p>
                                <p class="mb-1"><strong>Estado:</strong> <?= htmlspecialchars($pedido['estado_pedido']); ?></p>
                                <button class="btn btn-warning btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#reviewModal-<?= $pedido['id_pedido']; ?>">Añadir Reseña</button>
                                <div class="modal fade" id="reviewModal-<?= $pedido['id_pedido']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <form action="../funciones/valoraciones/insertar_valoracion.php" method="POST">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Añadir Reseña para Pedido #<?= $pedido['id_pedido']; ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_pedido" value="<?= $pedido['id_pedido']; ?>">
                                                    <div class="mb-3">
                                                        <label for="cantidad_estrellas" class="form-label">Calificación (1-5 estrellas)</label>
                                                        <select name="cantidad_estrellas" class="form-select" required>
                                                            <option value="" selected disabled>Selecciona una calificación</option>
                                                            <option value="1">1 estrella</option>
                                                            <option value="2">2 estrellas</option>
                                                            <option value="3">3 estrellas</option>
                                                            <option value="4">4 estrellas</option>
                                                            <option value="5">5 estrellas</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="comentario" class="form-label">Comentario</label>
                                                        <textarea name="comentario" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Enviar Reseña</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <p>No tienes pedidos registrados.</p>
                            <?php endif; ?>
                            <?php $stmt_pedidos->close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include '../includes/footer.php'; ?>
