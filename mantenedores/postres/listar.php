<?php
include '../../conexion.php'; 
$sql = "SELECT * FROM postre";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Postres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <?php if (isset($_GET['eliminado'])): ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            Postre ID <?php echo htmlspecialchars($_GET['id']); ?> eliminado exitosamente.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center">
        <h1>Listado de Postres</h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../index/index-mantenedores.php'">Volver</button>
    </div>
    <a href="insertar.php" class="btn btn-success mb-3">Agregar Nuevo Postre</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_postre']; ?></td>
                        <td><?php echo $row['nombre_postre']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo $row['precio']; ?></td>
                        <td>
                            <?php if (!empty($row['imagen'])): ?>
                                <img src="../../uploads/postres/<?php echo htmlspecialchars($row['imagen']); ?>" alt="Imagen" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="editar.php?id=<?php echo $row['id_postre']; ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-id="<?php echo $row['id_postre']; ?>"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No se encontraron postres.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal para Confirmación de Eliminación -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro que deseas eliminar este postre?
            </div>
            <div class="modal-footer">
                <form id="eliminarForm" action="eliminar.php" method="POST">
                    <input type="hidden" name="id_postre" id="id_postre" value="">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para pasar el ID al formulario dentro del modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var eliminarModal = document.getElementById('eliminarModal');
    eliminarModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Botón que abrió el modal
        var idPostre = button.getAttribute('data-id'); // Extrae el ID del postre
        var modalForm = document.getElementById('eliminarForm');
        var inputIdPostre = modalForm.querySelector('#id_postre');
        inputIdPostre.value = idPostre; // Coloca el ID en el input oculto del formulario
    });
</script>

</body>
</html>


