<?php
include '../../conexion.php';

// Consulta para obtener los pedidos con la información del usuario, promoción, total y fecha del pedido
$sql = "SELECT p.id_pedido, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario, pr.descripcion AS promocion, 
               p.total, p.fecha_pedido, p.estado_pedido, u.correo_electronico
        FROM pedido p
        INNER JOIN usuario u ON p.id_usuario = u.id_usuario
        LEFT JOIN promocion pr ON p.id_promocion = pr.id_promocion
        ORDER BY p.id_pedido ASC";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <?php if (isset($_GET['sin_cambio'])): ?>
            <div class='alert alert-info alert-dismissible fade show' role='alert'>
                No hubo cambios en el estado del pedido.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center">
            <h1>Listado de Pedidos</h1>
            <button class="btn btn-secondary" onclick="window.location.href='../../index/index.php'">Volver</button>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Promoción</th>
                    <th>total Total</th>
                    <th>Fecha y Hora del Pedido</th>
                    <th>Estado del Pedido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_pedido']; ?></td>
                            <td><?php echo $row['nombre_usuario'] . ' ' . $row['apellido_usuario']; ?></td>
                            <td><?php echo $row['promocion'] ? $row['promocion'] : 'Sin Promoción'; ?></td>
                            <td><?php echo '$' . number_format($row['total'], 2); ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['fecha_pedido'])); ?></td>
                            <td>
                                <span class="status" data-bs-toggle="modal" data-bs-target="#editStatusModal"
                                    data-id="<?php echo $row['id_pedido']; ?>"
                                    data-estado="<?php echo $row['estado_pedido']; ?>"
                                    data-correo="<?php echo $row['correo_electronico']; ?>">
                                    <?php echo ucfirst($row['estado_pedido']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="editar.php?id=<?php echo $row['id_pedido']; ?>"
                                    class="btn btn-primary btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal"
                                    data-id="<?php echo $row['id_pedido']; ?>">Eliminar</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron pedidos.</td>
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
                    ¿Estás seguro que deseas eliminar este pedido?
                </div>
                <div class="modal-footer">
                    <form id="eliminarForm" action="eliminar.php" method="POST">
                        <input type="hidden" name="id_pedido" id="id_pedido" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Estado del Pedido -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">Actualizar Estado del Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="actualizar_estado.php" method="POST">
                        <input type="hidden" name="id_pedido" id="editIdPedido" value="">
                        <input type="hidden" name="correo_usuario" id="correoUsuario" value="">
                        <input type="hidden" name="estado_actual" id="estadoActual" value="">

                        <div class="mb-3">
                            <label for="nuevo_estado" class="form-label">Nuevo Estado</label>
                            <select class="form-select" name="nuevo_estado" id="nuevo_estado" required>
                                <option value="en_preparacion">En Preparación</option>
                                <option value="en_reparto">En Reparto</option>
                                <option value="entregado">Entregado</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para pasar el ID, correo y estado actual al formulario en el modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var editStatusModal = document.getElementById('editStatusModal');
        editStatusModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var idPedido = button.getAttribute('data-id');
            var correoUsuario = button.getAttribute('data-correo');
            var estadoActual = button.getAttribute('data-estado');

            document.getElementById('editIdPedido').value = idPedido;
            document.getElementById('correoUsuario').value = correoUsuario;
            document.getElementById('estadoActual').value = estadoActual;
        });
    </script>

</body>

</html>
