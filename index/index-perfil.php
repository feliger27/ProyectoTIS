<?php
include '../conexion.php';
session_start();

// Mostrar el mensaje de éxito si está configurado
if (isset($_SESSION['mensaje_exito'])) {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='mensajeExito'>
            {$_SESSION['mensaje_exito']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    unset($_SESSION['mensaje_exito']); // Eliminar el mensaje después de mostrarlo
}

$user_id = $_SESSION['user_id']; // ID del usuario actual

// Consulta para obtener la información del usuario
$query_user = "SELECT nombre, apellido, correo_electronico, telefono FROM usuario WHERE id_usuario = ?";
$stmt_user = $conexion->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();
$stmt_user->close();

// Consulta para obtener direcciones del usuario actual
$query_direcciones = "SELECT d.id_direccion, d.calle, d.numero, d.ciudad, d.codigo_postal
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
    <link href="style.css" rel="stylesheet">
    <title>Mi Perfil - HamburGeeks</title>
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">Mi Cuenta</h2>
        <div class="row">
            <!-- Menú lateral -->
            <div class="col-md-3">
                <ul class="list-group">
                    <li class="list-group-item"><a href="#personal-info" data-bs-toggle="tab">Información Personal</a></li>
                    <li class="list-group-item"><a href="#manage-addresses" data-bs-toggle="tab">Gestionar Direcciones</a></li>
                    <li class="list-group-item"><a href="#settings" data-bs-toggle="tab">Configuración</a></li>
                </ul>
            </div>

            <!-- Contenido de cada sección -->
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Información Personal -->
                    <div class="tab-pane fade show active" id="personal-info">
                        <h3>Información Personal</h3>
                        <form action="../mantenedores/usuarios/editar.php?id=<?php echo $user_id; ?>&origin=perfil" method="POST">
                            <div class="form-group mb-3">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user_data['nombre']); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="apellido">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($user_data['apellido']); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="correo_electronico" value="<?php echo htmlspecialchars($user_data['correo_electronico']); ?>" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user_data['telefono']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </form>
                    </div>

                    <!-- Gestión de Direcciones -->
                    <div class="tab-pane fade" id="manage-addresses">
                        <h3>Gestionar Direcciones</h3>
                        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addAddressModal">Añadir Nueva Dirección</button>
                        
                        <!-- Lista de direcciones del usuario actual -->
                        <ul class="list-group">
                            <?php foreach ($direcciones as $direccion): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <?php echo htmlspecialchars($direccion['calle']) . ' ' . htmlspecialchars($direccion['numero']) . ', ' . htmlspecialchars($direccion['ciudad']) . ', ' . htmlspecialchars($direccion['codigo_postal']); ?>
                                    </span>
                                    <div>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAddressModal-<?php echo $direccion['id_direccion']; ?>">Editar</button>
                                        <a href="../funciones/gestionar_direcciones/eliminar_direccion.php?id_direccion=<?php echo $direccion['id_direccion']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta dirección?')">Eliminar</a>
                                    </div>
                                </li>
                                <!-- Modal para Editar Dirección -->
                                <div class="modal fade" id="editAddressModal-<?php echo $direccion['id_direccion']; ?>" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editAddressModalLabel">Editar Dirección</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../funciones/gestionar_direcciones/editar_direccion.php" method="POST">
                                                    <input type="hidden" name="id_direccion" value="<?php echo $direccion['id_direccion']; ?>">
                                                    <div class="form-group mb-3">
                                                        <label for="calle">Calle</label>
                                                        <input type="text" class="form-control" id="calle" name="calle" value="<?php echo htmlspecialchars($direccion['calle']); ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="numero">Número</label>
                                                        <input type="text" class="form-control" id="numero" name="numero" value="<?php echo htmlspecialchars($direccion['numero']); ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="ciudad">Ciudad</label>
                                                        <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($direccion['ciudad']); ?>" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="codigoPostal">Código Postal</label>
                                                        <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" value="<?php echo htmlspecialchars($direccion['codigo_postal']); ?>" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </ul>

                        <!-- Modal para Añadir Dirección -->
                        <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addAddressModalLabel">Añadir Nueva Dirección</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="../funciones/gestionar_direcciones/insertar_direccion.php" method="POST">
                                            <div class="form-group mb-3">
                                                <label for="calle">Calle</label>
                                                <input type="text" class="form-control" id="calle" name="calle" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="numero">Número</label>
                                                <input type="text" class="form-control" id="numero" name="numero" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="ciudad">Ciudad</label>
                                                <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="codigoPostal">Código Postal</label>
                                                <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración -->
                    <div class="tab-pane fade" id="settings">
                        <h3>Configuración</h3>
                        <p>Opciones de configuración del perfil.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Ocultar el mensaje después de 5 segundos
    setTimeout(function() {
        var mensajeExito = document.getElementById('mensajeExito');
        if (mensajeExito) {
            mensajeExito.classList.remove('show');
            mensajeExito.classList.add('fade');
        }
    }, 5000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
