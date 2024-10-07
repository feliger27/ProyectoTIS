<?php
include '../../conexion.php'; 

// Consulta para obtener los combos y sus complementos
$sql = "
    SELECT c.*, 
           GROUP_CONCAT(DISTINCT h.nombre_hamburguesa) AS hamburguesas,
           GROUP_CONCAT(DISTINCT a.nombre_acompanamiento) AS acompanamientos,
           GROUP_CONCAT(DISTINCT b.nombre_bebida) AS bebidas,
           GROUP_CONCAT(DISTINCT p.nombre_postre) AS postres
    FROM combo c
    LEFT JOIN combo_hamburguesa ch ON c.id_combo = ch.id_combo
    LEFT JOIN hamburguesa h ON ch.id_hamburguesa = h.id_hamburguesa
    LEFT JOIN combo_acompanamiento ca ON c.id_combo = ca.id_combo
    LEFT JOIN acompanamiento a ON ca.id_acompanamiento = a.id_acompanamiento
    LEFT JOIN combo_bebida cb ON c.id_combo = cb.id_combo
    LEFT JOIN bebida b ON cb.id_bebida = b.id_bebida
    LEFT JOIN combo_postre cp ON c.id_combo = cp.id_combo
    LEFT JOIN postre p ON cp.id_postre = p.id_postre
    GROUP BY c.id_combo
";

$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Combos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'eliminado'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            El combo ha sido eliminado exitosamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <div class="d-flex justify-content-between align-items-center">
        <h1>Listado de Combos</h1>
        <button class="btn btn-secondary" onclick="window.location.href='../../index.php'">Volver</button>
    </div>
    <a href="insertar.php" class="btn btn-success mb-3">Agregar Nuevo Combo</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Hamburguesas</th>
                <th>Acompañamientos</th>
                <th>Bebidas</th>
                <th>Postres</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_combo']; ?></td>
                        <td><?php echo $row['nombre_combo']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td><?php echo $row['precio']; ?></td>
                        <td><?php echo $row['hamburguesas'] ?: 'Ninguna'; ?></td>
                        <td><?php echo $row['acompanamientos'] ?: 'Ninguno'; ?></td>
                        <td><?php echo $row['bebidas'] ?: 'Ninguna'; ?></td>
                        <td><?php echo $row['postres'] ?: 'Ninguno'; ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row['id_combo']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <!-- Botón para activar el modal de eliminación -->
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-id="<?php echo $row['id_combo']; ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No se encontraron combos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este combo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <!-- Este es el botón que ejecutará la eliminación -->
                <a href="#" id="confirmarEliminar" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Script para pasar el ID del combo al enlace de confirmación de eliminación
var eliminarModal = document.getElementById('eliminarModal');
eliminarModal.addEventListener('show.bs.modal', function (event) {
    // Botón que activó el modal
    var button = event.relatedTarget;
    // Extraer el ID del combo del atributo data-id
    var idCombo = button.getAttribute('data-id');
    // Seleccionar el botón de confirmación de eliminación
    var confirmarEliminar = document.getElementById('confirmarEliminar');
    // Actualizar el enlace del botón con el ID del combo
    confirmarEliminar.href = 'eliminar.php?id=' + idCombo;
});
</script>

</body>
</html>

