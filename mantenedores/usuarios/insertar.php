<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el campo 'id_rol' existe en el array $_POST
    if (isset($_POST['id_rol'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo_electronico = $_POST['correo_electronico'];
        $contrasenia = password_hash($_POST['contrasenia'], PASSWORD_DEFAULT); // Encriptar la contraseña
        $telefono = $_POST['telefono'];
        $id_rol = $_POST['id_rol'];  // Asignar el ID del rol desde el formulario

        $sql = "INSERT INTO usuario (nombre, apellido, correo_electronico, contrasenia, telefono) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $apellido, $correo_electronico, $contrasenia, $telefono);

        if ($stmt->execute()) {
            // Obtener el ID del nuevo usuario
            $id_usuario = $stmt->insert_id;

            // Asignar el rol al usuario en la tabla usuario_rol
            $sql_rol = "INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)";
            $stmt_rol = $conexion->prepare($sql_rol);
            $stmt_rol->bind_param("ii", $id_usuario, $id_rol);

            if ($stmt_rol->execute()) {
                echo "<div class='container mt-3'>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            Usuario agregado exitosamente.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                      </div>";
            } else {
                echo "<div class='container mt-3'>
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Error al asignar el rol: " . $stmt_rol->error . "
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                      </div>";
            }
        } else {
            echo "<div class='container mt-3'>
                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        Error: " . $stmt->error . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                  </div>";
        }
    } else {
        echo "<div class='container mt-3'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    Error: No se ha seleccionado un rol.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
              </div>";
    }
}

// Obtener roles desde la tabla 'rol'
$query_roles = "SELECT id_rol, nombre_rol FROM rol";
$result_roles = $conexion->query($query_roles);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h1>Agregar Nuevo Usuario</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
        </div>
        <div class="mb-3">
            <label for="contrasenia" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
        </div>
        <div class="mb-3">
            <label for="id_rol" class="form-label">Rol</label>
            <select class="form-select" id="id_rol" name="id_rol" required>
                <?php
                // Generar las opciones de roles dinámicamente
                while ($row_rol = $result_roles->fetch_assoc()) {
                    echo '<option value="' . $row_rol['id_rol'] . '">' . $row_rol['nombre_rol'] . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Agregar Usuario</button>
        <a href="listar.php" class="btn btn-secondary">Volver</a>
    </form>
</div>

</body>
</html>


