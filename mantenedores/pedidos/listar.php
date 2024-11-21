<?php
include '../../conexion.php';
require_once '../../funciones/notificar usuario/enviar_notificacion.php'; // Incluye el archivo correcto

// Actualizar el estado del pedido si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'], $_POST['estado_pedido'])) {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['estado_pedido'];

    // Obtener el estado actual del pedido y el correo del cliente
    $sql_actual = "SELECT estado_pedido, u.correo_electronico 
                   FROM pedido p
                   JOIN usuario u ON p.id_usuario = u.id_usuario
                   WHERE p.id_pedido = ?";
    $stmt_actual = $conexion->prepare($sql_actual);
    $stmt_actual->bind_param("i", $id_pedido);
    $stmt_actual->execute();
    $result_actual = $stmt_actual->get_result();
    $pedido = $result_actual->fetch_assoc();
    $estado_actual = $pedido['estado_pedido'];
    $correo_usuario = $pedido['correo_electronico'];

    // Actualizar solo si el estado cambia
    if ($estado_actual !== $nuevo_estado) {
        $sql_update = "UPDATE pedido SET estado_pedido = ? WHERE id_pedido = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("si", $nuevo_estado, $id_pedido);
        
        if ($stmt_update->execute()) {
            $mensaje_exito = "El estado del pedido #$id_pedido se actualizó correctamente a '$nuevo_estado'.";

            // Enviar notificación al cliente usando enviarCorreoNotificacion
            $notificacion_result = enviarCorreoNotificacion($id_pedido, $nuevo_estado, $correo_usuario);
            if ($notificacion_result !== true) {
                $mensaje_error = "Estado actualizado, pero ocurrió un error al enviar la notificación: $notificacion_result";
            }
        } else {
            $mensaje_error = "Error al actualizar el estado del pedido: " . $stmt_update->error;
        }
    }
}

// Consulta para obtener los pedidos con la información del usuario
$sql = "SELECT p.id_pedido, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario, p.total, p.estado_pedido
        FROM pedido p
        INNER JOIN usuario u ON p.id_usuario = u.id_usuario";
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
    <?php if (isset($mensaje_exito)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $mensaje_exito; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($mensaje_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $mensaje_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <th>Total</th>
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
                        <td><?php echo '$' . number_format($row['total'], 2); ?></td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="id_pedido" value="<?php echo $row['id_pedido']; ?>">
                                <select name="estado_pedido" id="estado_<?php echo $row['id_pedido']; ?>" class="form-select me-2">
                                    <option value="en_preparacion" <?php if ($row['estado_pedido'] == 'en_preparacion') echo 'selected'; ?>>En preparación</option>
                                    <option value="en_reparto" <?php if ($row['estado_pedido'] == 'en_reparto') echo 'selected'; ?>>En reparto</option>
                                    <option value="entregado" <?php if ($row['estado_pedido'] == 'entregado') echo 'selected'; ?>>Entregado</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                            </form>
                        </td>
                        <td>
                            <a href="editar.php?id=<?php echo $row['id_pedido']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal" data-id="<?php echo $row['id_pedido']; ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No se encontraron pedidos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
