<?php
include '../../conexion.php';

if (isset($_GET['id'])) {
    $id_pedido = $_GET['id'];

    // Obtener el pedido actual junto con el nombre y correo del usuario
    $sql = "SELECT pedido.*, usuario.correo_electronico AS correo_usuario, usuario.nombre AS nombre 
            FROM pedido 
            JOIN usuario ON pedido.id_usuario = usuario.id_usuario 
            WHERE pedido.id_pedido = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $id_pedido);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedido = $result->fetch_assoc();

    // Obtener promociones para el formulario
    $promociones = $conexion->query("SELECT id_promocion, codigo_promocion FROM promocion");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Recibir datos del formulario
        $id_usuario = $_POST['id_usuario'];
        $id_promocion = $_POST['id_promocion'];
        $total = $_POST['total'];
        $estado_pedido = $_POST['estado_pedido'];

        // Obtener el correo del usuario seleccionado
        $stmt_usuario = $conexion->prepare("SELECT correo_electronico FROM usuario WHERE id_usuario = ?");
        $stmt_usuario->bind_param('i', $id_usuario);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();
        $correo_usuario = $result_usuario->fetch_assoc()['correo_electronico'];

        // Actualizar el pedido
        $sql_update = "UPDATE pedido SET id_usuario = ?, id_promocion = ?, total = ?, estado_pedido = ? WHERE id_pedido = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param('iiisi', $id_usuario, $id_promocion, $total, $estado_pedido, $id_pedido);

        if ($stmt_update->execute()) {
            $mensaje_exito = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                 Pedido editado exitosamente.
                                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                             </div>";

            // Enviar notificación si se seleccionó un estado diferente al actual
            if (isset($_POST['enviar_notificacion']) && $_POST['enviar_notificacion'] == '1') {
                $mensaje_notificacion = enviarCorreoNotificacion($id_pedido, $estado_pedido, $correo_usuario);
            }
        } else {
            $mensaje_error = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                Error: " . $stmt_update->error . "
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                             </div>";
        }
    }
} else {
    header('Location: listar.php');
    exit();
}

// Función para enviar correo de notificación
function enviarCorreoNotificacion($id_pedido, $estado_pedido, $correo)
{
    require '../../libs/PHPMailer/src/Exception.php';
    require '../../libs/PHPMailer/src/PHPMailer.php';
    require '../../libs/PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '02508a3a3a9bf5';
        $mail->Password = '99c3b27d1457ed';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 25;
        $mail->CharSet = 'UTF-8';  // Establece la codificación de caracteres en UTF-8

        // Configurar correo de origen y destinatario
        $mail->setFrom('from@example.com', 'Tu Proyecto');
        $mail->addAddress($correo);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Actualización de Estado del Pedido';
        $mail->Body    = "El estado de su pedido con ID #$id_pedido ha cambiado a: $estado_pedido.";
        $mail->AltBody = "El estado de su pedido con ID #$id_pedido ha cambiado a: $estado_pedido.";

        $mail->send();
        return "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                   La notificación fue enviada exitosamente a $correo.
                   <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
               </div>";
    } catch (Exception $e) {
        return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                   Error al enviar el mensaje: {$mail->ErrorInfo}
                   <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
               </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <?php
    if (isset($mensaje_exito)) {
        echo $mensaje_exito;
    }
    if (isset($mensaje_notificacion)) {
        echo $mensaje_notificacion;
    }
    if (isset($mensaje_error)) {
        echo $mensaje_error;
    }
    ?>
    <div class="d-flex justify-content-between align-items-center">
        <h1>Editar Pedido</h1>
        <button class="btn btn-secondary" onclick="window.location.href='listar.php'">Volver</button>
    </div>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="id_usuario" class="form-label">Usuario</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($pedido['nombre'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
            <input type="hidden" name="id_usuario" value="<?php echo $pedido['id_usuario']; ?>">
        </div>

        <div class="mb-3">
            <label for="id_promocion" class="form-label">Promoción</label>
            <select class="form-select" id="id_promocion" name="id_promocion" required>
                <?php while ($promocion = $promociones->fetch_assoc()): ?>
                    <option value="<?php echo $promocion['id_promocion']; ?>" <?php if ($pedido['id_promocion'] == $promocion['id_promocion']) echo 'selected'; ?>>
                        <?php echo $promocion['codigo_promocion']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total</label>
            <input type="number" class="form-control" id="total" name="total" value="<?php echo $pedido['total']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="estado_pedido" class="form-label">Estado del Pedido</label>
            <select class="form-select" id="estado_pedido" name="estado_pedido" onchange="verificarCambioEstado()" required>
                <option value="en_preparacion" <?php if ($pedido['estado_pedido'] == 'en_preparacion') echo 'selected'; ?>>En preparación</option>
                <option value="en_reparto" <?php if ($pedido['estado_pedido'] == 'en_reparto') echo 'selected'; ?>>En reparto</option>
                <option value="entregado" <?php if ($pedido['estado_pedido'] == 'entregado') echo 'selected'; ?>>Entregado</option>
            </select>
        </div>

        <input type="hidden" name="correo_usuario" value="<?php echo $pedido['correo_usuario']; ?>">

        <!-- Checkbox para enviar notificación -->
        <div id="notificacionFormulario" style="display: none;" class="mb-3">
            <input type="hidden" name="enviar_notificacion" value="1">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="enviarNotificacion" name="enviar_notificacion">
                <label class="form-check-label" for="enviarNotificacion">Enviar notificación al usuario</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="listar.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
function verificarCambioEstado() {
    const estadoActual = '<?php echo $pedido['estado_pedido']; ?>';
    const estadoSeleccionado = document.getElementById('estado_pedido').value;
    const notificacionFormulario = document.getElementById('notificacionFormulario');

    if (estadoSeleccionado !== estadoActual) {
        notificacionFormulario.style.display = 'block';
    } else {
        notificacionFormulario.style.display = 'none';
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

