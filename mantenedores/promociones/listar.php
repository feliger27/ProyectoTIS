<?php
include '../../conexion.php'; 
$sql = "SELECT * FROM promocion";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Promociones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <?php if (isset($_GET['eliminado'])): ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            Promoción ID <?php echo htmlspecialchars($_GET['id']); ?> eliminada exitosamente.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center">
        <h1>Listado de Promociones</h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../index/index-mantenedores.php'">Volver</button>
    </div>
    <a href="insertar.php" class="btn btn-success mb-3">Agregar Nueva Promoción</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Porcentaje Descuento</th>
                <th>Condiciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_promocion']; ?></td>
                        <td><?php echo $row['codigo_promocion']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td><?php echo $row['fecha_inicio']; ?></td>
                        <td><?php echo $row['fecha_fin']; ?></td>
                        <td><?php echo $row['porcentaje_descuento']; ?>%</td>
                        <td><?php echo $row['condiciones']; ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row['id_promocion']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-id="<?php echo $row['id_promocion']; ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No se encontraron promociones.</td>
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
                ¿Estás seguro que deseas eliminar esta promoción?
            </div>
            <div class="modal-footer">
                <form id="eliminarForm" action="eliminar.php" method="POST">
                    <input type="hidden" name="id_promocion" id="id_promocion" value="">
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
        var idPromocion = button.getAttribute('data-id'); // Extrae el ID de la promoción
        var modalForm = document.getElementById('eliminarForm');
        var inputIdPromocion = modalForm.querySelector('#id_promocion');
        inputIdPromocion.value = idPromocion; // Coloca el ID en el input oculto del formulario
    });
</script>

</body>
</html>
