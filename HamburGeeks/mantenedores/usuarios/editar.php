<?php
include '../../conexion.php'; 

if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id_usuario = $_GET['id'];

// Consultar el usuario a editar
$sql = "SELECT u.*, ur.id_rol FROM usuario u LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario WHERE u.id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// Obtener roles desde la tabla 'rol'
$query_roles = "SELECT id_rol, nombre_rol FROM rol";
$result_roles = $conexion->query($query_roles);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo_electronico = $_POST['correo_electronico'];
    $telefono = $_POST['telefono'];
    $id_rol = $_POST['id_rol'];  // Asignar el ID del rol desde el formulario

    // Actualizar la información del usuario
    $sql_usuario = "UPDATE usuario SET nombre = ?, apellido = ?, correo_electronico = ?, telefono = ? WHERE id_usuario = ?";
    $stmt_usuario = $conexion->prepare($sql_usuario);
    $stmt_usuario->bind_param("ssssi", $nombre, $apellido, $correo_electronico, $telefono, $id_usuario);

    if ($stmt_usuario->execute()) {
        // Actualizar o insertar la relación en usuario_rol
        $sql_usuario_rol = "REPLACE INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)";
        $stmt_usuario_rol = $conexion->prepare($sql_usuario_rol);
        $stmt_usuario_rol->bind_param("ii", $id_usuario, $id_rol);

        if ($stmt_usuario_rol->execute()) {
            echo "<div class='container mt-3'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                        Usuario editado exitosamente.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                  </div>";
        } else {
            echo "<div class='container mt-3'>
                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Error al asignar el rol: " . $stmt_usuario_rol->error . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                  </div>";
        }
        $stmt_usuario_rol->close();
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
    <h1>Editar Usuario</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $usuario['apellido']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo $usuario['correo_electronico']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $usuario['telefono']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="id_rol" class="form-label">Rol</label>
            <select class="form-select" id="id_rol" name="id_rol" required>
                <?php
                // Generar las opciones de roles dinámicamente
                while ($row_rol = $result_roles->fetch_assoc()) {
                    $selected = ($row_rol['id_rol'] == $usuario['id_rol']) ? 'selected' : '';
                    echo '<option value="' . $row_rol['id_rol'] . '" ' . $selected . '>' . $row_rol['nombre_rol'] . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
        <a href="listar.php" class="btn btn-secondary">Volver</a>
    </form>
</div>

</body>
</html>

