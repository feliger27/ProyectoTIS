<?php
include '../../conexion.php'; 

// Consulta para obtener todas las hamburguesas, sus ingredientes y aderezos
$sql = "SELECT h.id_hamburguesa, h.nombre_hamburguesa, h.precio, 
               GROUP_CONCAT(DISTINCT CONCAT(i.nombre_ingrediente, ' (', hi.cantidad, ')') SEPARATOR ', ') AS ingredientes,
               GROUP_CONCAT(DISTINCT a.nombre_aderezo SEPARATOR ', ') AS aderezos
        FROM hamburguesa h
        LEFT JOIN hamburguesa_ingrediente hi ON h.id_hamburguesa = hi.id_hamburguesa
        LEFT JOIN ingrediente i ON hi.id_ingrediente = i.id_ingrediente
        LEFT JOIN hamburguesa_aderezo ha ON h.id_hamburguesa = ha.id_hamburguesa
        LEFT JOIN aderezo a ON ha.id_aderezo = a.id_aderezo
        GROUP BY h.id_hamburguesa";



$result = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Hamburguesas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <?php if (isset($_GET['eliminado'])): ?>
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            Hamburguesa ID <?php echo htmlspecialchars($_GET['id']); ?> eliminada exitosamente.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center">
        <h1>Listado de Hamburguesas</h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../index.php'">Volver</button>
    </div>
    <a href="insertar.php" class="btn btn-success mb-3">Agregar Nueva Hamburguesa</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Ingredientes</th>
                <th>Aderezos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_hamburguesa']; ?></td>
                        <td><?php echo $row['nombre_hamburguesa']; ?></td>
                        <td><?php echo $row['precio']; ?></td>
                        <td><?php echo $row['ingredientes'] ? $row['ingredientes'] : 'Sin ingredientes'; ?></td>
                        <td><?php echo $row['aderezos'] ? $row['aderezos'] : 'Sin aderezos'; ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row['id_hamburguesa']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-id="<?php echo $row['id_hamburguesa']; ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No se encontraron hamburguesas.</td>
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
                ¿Estás seguro que deseas eliminar esta hamburguesa?
            </div>
            <div class="modal-footer">
                <form id="eliminarForm" action="eliminar.php" method="POST">
                    <input type="hidden" name="id_hamburguesa" id="id_hamburguesa" value="">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script para pasar el ID al formulario dentro del modal
    var eliminarModal = document.getElementById('eliminarModal');
    eliminarModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Botón que abrió el modal
        var idHamburguesa = button.getAttribute('data-id'); // Extrae el ID de la hamburguesa
        var modalForm = document.getElementById('eliminarForm');
        var inputIdHamburguesa = modalForm.querySelector('#id_hamburguesa');
        inputIdHamburguesa.value = idHamburguesa; // Coloca el ID en el input oculto del formulario
    });
</script>

</body>
</html>


