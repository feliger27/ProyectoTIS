<?php
include '../../conexion.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigir si el usuario no ha iniciado sesión
    exit;
}

$id_usuario = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];
$origin = isset($_GET['origin']) ? $_GET['origin'] : ''; // Detectar el origen de acceso

// Verificar si el usuario tiene permiso para editar usuarios
$tiene_permiso_editar_usuario = in_array('editar_usuario', $_SESSION['permissions']);

// Consultar el usuario a editar
$sql = "SELECT u.*, ur.id_rol FROM usuario u LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario WHERE u.id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    echo "<div class='container mt-3'>
            <div class='alert alert-danger' role='alert'>
                Usuario no encontrado.
            </div>
          </div>";
    exit;
}

$roles = [];
if ($tiene_permiso_editar_usuario) {
    $query_roles = "SELECT id_rol, nombre_rol FROM rol";
    $result_roles = $conexion->query($query_roles);
    $roles = $result_roles->fetch_all(MYSQLI_ASSOC);
}

// Comprobar si el usuario está intentando cambiar su propio rol a 'admin'
$is_current_user_admin = ($usuario['id_rol'] == 1); // Asumimos que '1' es el ID del rol admin

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo_electronico = !empty($_POST['correo_electronico']) ? $_POST['correo_electronico'] : $usuario['correo_electronico'];
    $telefono = $_POST['telefono'];
    $nuevo_rol = isset($_POST['id_rol']) ? $_POST['id_rol'] : null;

    // Si el usuario admin está intentando cambiar su rol a otro rol (que no sea admin)
    if ($is_current_user_admin && $nuevo_rol != 1) {
        $mensaje = "No puedes cambiar tu rol de 'admin' a otro rol.";
        $tipo_mensaje = 'danger'; // Mensaje de error
    } else {
        // Actualizar los datos del usuario
        $sql_usuario = "UPDATE usuario SET nombre = ?, apellido = ?, correo_electronico = ?, telefono = ? WHERE id_usuario = ?";
        $stmt_usuario = $conexion->prepare($sql_usuario);
        $stmt_usuario->bind_param("ssssi", $nombre, $apellido, $correo_electronico, $telefono, $id_usuario);

        if ($stmt_usuario->execute()) {
            // Si el usuario tiene permiso para editar roles y se ha enviado un nuevo rol
            if ($tiene_permiso_editar_usuario && isset($_POST['id_rol'])) {
                // Eliminar el rol anterior si existe
                $sql_delete_role = "DELETE FROM usuario_rol WHERE id_usuario = ?";
                $stmt_delete_role = $conexion->prepare($sql_delete_role);
                $stmt_delete_role->bind_param("i", $id_usuario);
                $stmt_delete_role->execute();
                $stmt_delete_role->close();

                // Insertar el nuevo rol
                $id_rol = $_POST['id_rol'];
                $sql_usuario_rol = "INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)";
                $stmt_usuario_rol = $conexion->prepare($sql_usuario_rol);
                $stmt_usuario_rol->bind_param("ii", $id_usuario, $id_rol);
                $stmt_usuario_rol->execute();
                $stmt_usuario_rol->close();
            }

            $mensaje = "Usuario editado exitosamente.";
            $tipo_mensaje = 'success'; // Mensaje de éxito
        } else {
            $mensaje = "Error: " . $stmt_usuario->error;
            $tipo_mensaje = 'danger'; // Mensaje de error
        }
        $stmt_usuario->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Agrega Bootstrap JS para los toasts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <!-- Mostrar la notificación si hay un mensaje -->
    <?php if (isset($mensaje)): ?>
        <!-- Toast de notificación -->
        <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-white bg-<?php echo $tipo_mensaje; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $mensaje; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <script>
            // Mostrar la notificación toast al cargar la página
            var toast = new bootstrap.Toast($('.toast')[0]);
            toast.show();
        </script>
    <?php endif; ?>

    <h1><?php echo $tiene_permiso_editar_usuario ? "Editar Usuario" : "Mi Perfil"; ?></h1>
    <form method="POST" action="">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label for="apellido" class="form-label">Apellido</label>
        <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label for="correo_electronico" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($usuario['correo_electronico']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" readonly>
    </div>

    <!-- Mostrar selección de rol solo si el usuario actual tiene permiso de administrador -->
    <?php if ($tiene_permiso_editar_usuario): ?>
        <div class="mb-3">
            <label for="id_rol" class="form-label">Rol</label>
            <select class="form-select" id="id_rol" name="id_rol" required>
                <?php foreach ($roles as $rol): ?>
                    <option value="<?php echo $rol['id_rol']; ?>" <?php echo $rol['id_rol'] == $usuario['id_rol'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else: ?>
        <div class="mb-3">
            <label for="rol_actual" class="form-label">Rol</label>
            <input type="text" class="form-control" value="<?php foreach ($roles as $rol) { if ($rol['id_rol'] == $usuario['id_rol']) { echo htmlspecialchars($rol['nombre_rol']); break; } } ?>" readonly>
        </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="<?php echo $origin === 'perfil' ? '../index-perfil.php' : 'listar.php'; ?>" class="btn btn-secondary">Volver</a>
</form>

</div>

</body>
</html>






