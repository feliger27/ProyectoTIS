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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Mi Perfil - HamburGeeks</title>
</head>

<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center">Mi Perfil</h2>
            <div>
                <a href="../index/index-lobby.php" class="btn btn-secondary me-2">Volver al Lobby</a>
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
                                        <?= htmlspecialchars($direccion['calle']) . ' ' . htmlspecialchars($direccion['numero']) . ', ' . htmlspecialchars($direccion['ciudad']); ?>
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
                                <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <div class="card">
                    <div class="card-body">
                        <h4>Configuración</h4>
                        <p>Opciones de configuración del perfil.</p>
                    </div>
                </div>
            </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            var lastTab = localStorage.getItem('activeTab');
            if (lastTab) {
                var selectedTab = document.querySelector(`#perfil-tabs button[data-bs-target="${lastTab}"]`);
                var selectedTabContent = document.querySelector(lastTab);
                if (selectedTab && selectedTabContent) {
                    document.querySelector(`#perfil-tabs .nav-link.active`).classList.remove('active');
                    document.querySelector(`.tab-content .tab-pane.show.active`).classList.remove('show', 'active');
                    selectedTab.classList.add('active');
                    selectedTabContent.classList.add('show', 'active');
                }
            }

            document.querySelectorAll('#perfil-tabs button').forEach(function (tabButton) {
                tabButton.addEventListener('click', function () {
                    var target = tabButton.getAttribute('data-bs-target');
                    localStorage.setItem('activeTab', target);
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php include '../includes/footer.php'; ?>
