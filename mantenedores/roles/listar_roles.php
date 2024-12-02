<?php
include '../../conexion.php'; 

if (isset($_SESSION['mensaje_exito'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['mensaje_exito'] . "</div>";
    unset($_SESSION['mensaje_exito']);
}

if (isset($_SESSION['mensaje_error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['mensaje_error'] . "</div>";
    unset($_SESSION['mensaje_error']);
}
$query_roles = "SELECT * FROM rol";
$roles = $conexion->query($query_roles);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Roles</h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../index/index-mantenedores.php'">Volver</button>
    </div>
    <a href="crear_rol.php" class="btn btn-primary mb-3">Crear Rol</a>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Permisos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $roles->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_rol']; ?></td>
                    <td><?php echo $row['nombre_rol']; ?></td>
                    <td><?php echo $row['descripcion_rol']; ?></td>
                    <td>
                        <!-- Botón "Ver" para mostrar los permisos asociados al rol en un modal -->
                        <button class="btn btn-info" onclick="showPermissions(<?php echo $row['id_rol']; ?>)">Ver</button>
                    </td>
                    <td>
                        <a href="editar_rol.php?id_rol=<?php echo $row['id_rol']; ?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                        <a href="eliminar_rol.php?id_rol=<?php echo $row['id_rol']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este rol?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal para mostrar permisos -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionsModalLabel">Permisos del Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="permissionsList">
                <!-- Aquí se cargará la lista de permisos del rol -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Función para mostrar el modal y cargar permisos
function showPermissions(roleId) {
    fetch('ver_permisos.php?id=' + roleId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('permissionsList').innerHTML = data;
            var permissionsModal = new bootstrap.Modal(document.getElementById('permissionsModal'));
            permissionsModal.show();
        });
}
</script>
</body>
</html>

