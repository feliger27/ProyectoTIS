<?php
include '../../conexion.php';  // Asegúrate de que la ruta sea correcta

// Verificar si se ha proporcionado un ID
if (!isset($_GET['id'])) {
    header("Location: listar_permisos.php");
    exit();
}

$id_permiso = intval($_GET['id']);

// Obtener los datos actuales del permiso
$query = "SELECT * FROM permiso WHERE id_permiso = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_permiso);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: listar_permisos.php");
    exit();
}

$permiso = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_permiso = $_POST['nombre_permiso'];
    $descripcion = $_POST['descripcion'];

    // Validación básica
    if (empty($nombre_permiso)) {
        $error = "El nombre del permiso es obligatorio.";
    } else {
        $update_query = "UPDATE permiso SET nombre_permiso = ?, descripcion = ? WHERE id_permiso = ?";
        $update_stmt = $conexion->prepare($update_query);
        $update_stmt->bind_param("ssi", $nombre_permiso, $descripcion, $id_permiso);
        
        if ($update_stmt->execute()) {
            header("Location: listar_permisos.php");
            exit();
        } else {
            $error = "Error al actualizar el permiso: " . $update_stmt->error;
        }

        $update_stmt->close();
    }
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Permiso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Editar Permiso</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form action="editar_permiso.php?id=<?php echo $id_permiso; ?>" method="POST">
        <div class="mb-3">
            <label for="nombre_permiso" class="form-label">Nombre del Permiso</label>
            <input type="text" class="form-control" id="nombre_permiso" name="nombre_permiso" value="<?php echo htmlspecialchars($permiso['nombre_permiso']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($permiso['descripcion']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Permiso</button>
        <a href="listar_permisos.php" class="btn btn-secondary">Volver al Listado</a>
    </form>
</div>
</body>
</html>
