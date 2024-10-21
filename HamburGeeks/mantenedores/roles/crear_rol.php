<?php
// Archivo: crear_rol.php

// Incluir el archivo de conexión a la base de datos
include '../../conexion.php'; 

// Procesar la creación del rol y asignación de permisos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores desde el formulario
    $nombre_rol = $_POST['nombre_rol'];
    $descripcion_rol = $_POST['descripcion']; // Nueva descripción
    $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];

    // Insertar el nuevo rol en la base de datos
    $query_rol = "INSERT INTO rol (nombre_rol, descripcion) VALUES (?, ?)"; // Ajustar para incluir la descripción
    $stmt_rol = $conexion->prepare($query_rol);
    $stmt_rol->bind_param("ss", $nombre_rol, $descripcion_rol);
    $stmt_rol->execute();

    // Obtener el ID del rol recién creado
    $id_rol = $stmt_rol->insert_id;

    // Insertar los permisos seleccionados para el rol en la tabla rol_permiso
    if (!empty($permisos)) {
        $query_permiso = "INSERT INTO rol_permiso (id_rol, id_permiso) VALUES (?, ?)";
        $stmt_permiso = $conexion->prepare($query_permiso);

        // Insertar cada permiso seleccionado en la tabla rol_permiso
        foreach ($permisos as $id_permiso) {
            $stmt_permiso->bind_param("ii", $id_rol, $id_permiso);
            $stmt_permiso->execute();
        }
    }

    // Redirigir o mostrar un mensaje de éxito
    header("Location: listar_roles.php");
    exit();
}

// Obtener todos los permisos desde la base de datos para mostrarlos en el checklist
$query = "SELECT * FROM permiso";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
        <h1>Crear Nuevo rol</h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../listar_roles.php'">Volver</button>
        </div>
        <form action="crear_rol.php" method="POST">
            <!-- Campo para el nombre del rol -->
            <div class="mb-3">
                <label for="nombre_rol" class="form-label">Nombre del Rol</label>
                <input type="text" class="form-control" id="nombre_rol" name="nombre_rol" required>
            </div>

            <!-- Campo para la descripción del rol -->
            <div class="mb-3">
                <label for="descripcion_rol" class="form-label">Descripción del Rol</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>

            <!-- Checklist de permisos -->
            <div class="mb-3">
                <label for="permisos" class="form-label">Asignar Permisos:</label>
                <div class="form-check">
                    <?php
                    // Mostrar cada permiso como un checkbox
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input" type="checkbox" name="permisos[]" value="' . $row['id_permiso'] . '" id="permiso' . $row['id_permiso'] . '">';
                        echo '<label class="form-check-label" for="permiso' . $row['id_permiso'] . '">' . $row['nombre_permiso'] . '</label>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Botón para crear el rol -->
            <button type="submit" class="btn btn-primary">Crear Rol</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
