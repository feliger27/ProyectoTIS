<?php
include '../../conexion.php';

// Consulta para obtener las promociones, con la información de productos asociados
$sql = "
    SELECT p.*, 
           h.nombre_hamburguesa, 
           b.nombre_bebida, 
           po.nombre_postre, 
           a.nombre_acompaniamiento,
           c.nombre_combo
    FROM promocion p
    LEFT JOIN hamburguesa h ON p.id_hamburguesa = h.id_hamburguesa
    LEFT JOIN bebida b ON p.id_bebida = b.id_bebida
    LEFT JOIN postre po ON p.id_postre = po.id_postre
    LEFT JOIN acompaniamiento a ON p.id_acompaniamiento = a.id_acompaniamiento
    LEFT JOIN combo c ON p.id_combo = c.id_combo

";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Promociones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <!-- Alerta de eliminación exitosa -->
    <?php if (isset($_GET['eliminado'])): ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            Promoción eliminada exitosamente.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center">
        <h1>Listado de Promociones</h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../index/index-mantenedores.php'">Volver</button>
    </div>
    <a href="insertar_promocion.php" class="btn btn-success mb-3">Agregar Nueva Promoción</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Promoción</th>
                <th>Descripción</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Porcentaje Descuento</th>
                <th>Producto Asociado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_promocion']; ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_promocion']); ?></td>
                        <td><?php echo htmlspecialchars($row['descripcion_promocion']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_inicio']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
                        <td><?php echo htmlspecialchars($row['porcentaje_descuento']); ?>%</td>
                        <td>
                            <?php
                            // Mostrar el nombre del producto relacionado
                            if ($row['nombre_hamburguesa']) {
                                echo 'Hamburguesa: ' . htmlspecialchars($row['nombre_hamburguesa']);
                            } elseif ($row['nombre_bebida']) {
                                echo 'Bebida: ' . htmlspecialchars($row['nombre_bebida']);
                            } elseif ($row['nombre_postre']) {
                                echo 'Postre: ' . htmlspecialchars($row['nombre_postre']);
                            } elseif ($row['nombre_acompaniamiento']) {
                                echo 'Acompañamiento: ' . htmlspecialchars($row['nombre_acompaniamiento']);
                            } elseif ($row['nombre_combo']) {
                                echo 'combo: ' . htmlspecialchars($row['nombre_combo']);
                            } else {
                                echo 'No asignado';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="editar_promocion.php?id=<?php echo $row['id_promocion']; ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
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
                <form id="eliminarForm" action="eliminar_promocion.php" method="POST">
                    <input type="hidden" name="id_promocion" id="id_promocion" value=""/>
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
        var inputIdPromocion = document.getElementById('id_promocion');
        inputIdPromocion.value = idPromocion; // Coloca el ID en el input oculto del formulario
    });
</script>

</body>
</html>

