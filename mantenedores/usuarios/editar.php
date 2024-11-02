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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo_electronico = !empty($_POST['correo_electronico']) ? $_POST['correo_electronico'] : $usuario['correo_electronico'];
    $telefono = $_POST['telefono'];

    $sql_usuario = "UPDATE usuario SET nombre = ?, apellido = ?, correo_electronico = ?, telefono = ? WHERE id_usuario = ?";
    $stmt_usuario = $conexion->prepare($sql_usuario);
    $stmt_usuario->bind_param("ssssi", $nombre, $apellido, $correo_electronico, $telefono, $id_usuario);

    if ($stmt_usuario->execute()) {
        if ($tiene_permiso_editar_usuario && isset($_POST['id_rol'])) {
            $id_rol = $_POST['id_rol'];
            $sql_usuario_rol = "REPLACE INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)";
            $stmt_usuario_rol = $conexion->prepare($sql_usuario_rol);
            $stmt_usuario_rol->bind_param("ii", $id_usuario, $id_rol);
            $stmt_usuario_rol->execute();
            $stmt_usuario_rol->close();
        }
        echo "<div class='container mt-3'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                    Usuario editado exitosamente.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
              </div>";

        // Redirección condicional según el origen
        if ($origin === 'perfil') {
            $_SESSION['mensaje_exito'] = "Los cambios se han guardado correctamente.";
            header("Location: ../../index/index-perfil.php");
        } else {
            header("Location: listar.php");
        }
        exit();

    } else {
        echo "<div class='container mt-3'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Error: " . $stmt_usuario->error . "
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
              </div>";
    }
    $stmt_usuario->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h1><?php echo $tiene_permiso_editar_usuario ? "Editar Usuario" : "Mi Perfil"; ?></h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($usuario['correo_electronico']); ?>" <?php echo $tiene_permiso_editar_usuario ? '' : 'readonly'; ?>>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
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
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="<?php echo $origin === 'perfil' ? '../index-perfil.php' : 'listar.php'; ?>" class="btn btn-secondary">Volver</a>
    </form>
</div>

</body>
</html>



