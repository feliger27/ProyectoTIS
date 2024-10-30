<?php
include '../../conexion.php';  // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_permiso = $_POST['nombre_permiso'];
    $descripcion = $_POST['descripcion'];

    // Validación básica
    if (empty($nombre_permiso)) {
        $error = "El nombre del permiso es obligatorio.";
    } else {
        $query = "INSERT INTO permiso (nombre_permiso, descripcion) VALUES (?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $nombre_permiso, $descripcion);
        
        if ($stmt->execute()) {
            header("Location: listar_permisos.php");
            exit();
        } else {
            $error = "Error al crear el permiso: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Permiso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Crear Permiso</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form action="crear_permiso.php" method="POST">
        <div class="mb-3">
            <label for="nombre_permiso" class="form-label">Nombre del Permiso</label>
            <input type="text" class="form-control" id="nombre_permiso" name="nombre_permiso" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Crear Permiso</button>
        <a href="listar_permisos.php" class="btn btn-secondary">Volver al Listado</a>
    </form>
</div>
</body>
</html>
