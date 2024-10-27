<?php
include '../../conexion.php';  // Asegúrate de que la ruta sea correcta

$query = "SELECT * FROM permiso";
$permisos = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Permisos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Listado de Permisos</h1>
    <a href="crear_permiso.php" class="btn btn-primary mb-3">Crear Permiso</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $permisos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_permiso']; ?></td>
                    <td><?php echo $row['nombre_permiso']; ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td>
                        <a href="editar_permiso.php?id=<?php echo $row['id_permiso']; ?>" class="btn btn-warning">Editar</a>
                        <a href="eliminar_permiso.php?id=<?php echo $row['id_permiso']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este permiso?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
