<?php
include '../../conexion.php'; 

$query_roles = "SELECT * FROM rol";
$roles = $conexion->query($query_roles);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Listado de Roles</h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../index.php'">Volver</button>
    </div>
    <a href="crear_rol.php" class="btn btn-primary mb-3">Crear Rol</a>
    
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
            <?php while ($row = $roles->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_rol']; ?></td>
                    <td><?php echo $row['nombre_rol']; ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td>
                        <a href="editar_rol.php?id=<?php echo $row['id_rol']; ?>" class="btn btn-warning">Editar</a>
                        <a href="eliminar_rol.php?id=<?php echo $row['id_rol']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este rol?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
