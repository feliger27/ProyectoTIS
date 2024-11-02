<?php
// Archivo: editar_rol.php

// Incluir el archivo de conexión a la base de datos
include '../../conexion.php'; 

// Verificar si se recibió el ID del rol a editar
if (!isset($_GET['id_rol'])) {
    header("Location: listar_roles.php");
    exit();
}

$id_rol = $_GET['id_rol'];

// Obtener los datos actuales del rol
$query_rol = "SELECT nombre_rol, descripcion_rol FROM rol WHERE id_rol = ?";
$stmt_rol = $conexion->prepare($query_rol);
$stmt_rol->bind_param("i", $id_rol);
$stmt_rol->execute();
$result_rol = $stmt_rol->get_result();
$rol_data = $result_rol->fetch_assoc();
$stmt_rol->close();

// Obtener todos los permisos para mostrarlos en el checklist
$query_permisos = "SELECT * FROM permiso";
$result_permisos = $conexion->query($query_permisos);

// Obtener los permisos actuales del rol
$query_rol_permisos = "SELECT id_permiso FROM rol_permiso WHERE id_rol = ?";
$stmt_rol_permisos = $conexion->prepare($query_rol_permisos);
$stmt_rol_permisos->bind_param("i", $id_rol);
$stmt_rol_permisos->execute();
$result_rol_permisos = $stmt_rol_permisos->get_result();

$current_permissions = [];
while ($row = $result_rol_permisos->fetch_assoc()) {
    $current_permissions[] = $row['id_permiso'];
}
$stmt_rol_permisos->close();

// Procesar la edición del rol y asignación de permisos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores del formulario
    $nombre_rol = $_POST['nombre_rol'];
    $descripcion_rol = $_POST['descripcion_rol'];
    $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];

    // Actualizar el rol en la base de datos
    $query_update_rol = "UPDATE rol SET nombre_rol = ?, descripcion_rol = ? WHERE id_rol = ?";
    $stmt_update_rol = $conexion->prepare($query_update_rol);
    $stmt_update_rol->bind_param("ssi", $nombre_rol, $descripcion_rol, $id_rol);
    $stmt_update_rol->execute();
    $stmt_update_rol->close();

    // Actualizar los permisos asociados al rol
    // Eliminar los permisos actuales
    $query_delete_permissions = "DELETE FROM rol_permiso WHERE id_rol = ?";
    $stmt_delete_permissions = $conexion->prepare($query_delete_permissions);
    $stmt_delete_permissions->bind_param("i", $id_rol);
    $stmt_delete_permissions->execute();
    $stmt_delete_permissions->close();

    // Insertar los nuevos permisos
    if (!empty($permisos)) {
        $query_insert_permissions = "INSERT INTO rol_permiso (id_rol, id_permiso) VALUES (?, ?)";
        $stmt_insert_permissions = $conexion->prepare($query_insert_permissions);

        foreach ($permisos as $id_permiso) {
            $stmt_insert_permissions->bind_param("ii", $id_rol, $id_permiso);
            $stmt_insert_permissions->execute();
        }
        $stmt_insert_permissions->close();
    }

    // Redirigir o mostrar un mensaje de éxito
    $_SESSION['mensaje_exito'] = "Rol actualizado correctamente.";
    header("Location: listar_roles.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Rol</h2>
        <form action="editar_rol.php?id_rol=<?php echo $id_rol; ?>" method="POST">
            <!-- Campo para el nombre del rol -->
            <div class="mb-3">
                <label for="nombre_rol" class="form-label">Nombre del Rol</label>
                <input type="text" class="form-control" id="nombre_rol" name="nombre_rol" value="<?php echo htmlspecialchars($rol_data['nombre_rol']); ?>" required>
            </div>

            <!-- Campo para la descripción del rol -->
            <div class="mb-3">
                <label for="descripcion_rol" class="form-label">Descripción del Rol</label>
                <textarea class="form-control" id="descripcion_rol" name="descripcion_rol" rows="3" required><?php echo htmlspecialchars($rol_data['descripcion_rol']); ?></textarea>
            </div>

            <!-- Checklist de permisos -->
            <div class="mb-3">
                <label for="permisos" class="form-label">Asignar Permisos:</label>
                <div class="form-check">
                    <?php
                    // Mostrar cada permiso como un checkbox
                    while ($row = $result_permisos->fetch_assoc()) {
                        $checked = in_array($row['id_permiso'], $current_permissions) ? 'checked' : '';
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input" type="checkbox" name="permisos[]" value="' . $row['id_permiso'] . '" id="permiso' . $row['id_permiso'] . '" ' . $checked . '>';
                        echo '<label class="form-check-label" for="permiso' . $row['id_permiso'] . '">' . $row['nombre_permiso'] . '</label>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Botón para actualizar el rol -->
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="listar_roles.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
