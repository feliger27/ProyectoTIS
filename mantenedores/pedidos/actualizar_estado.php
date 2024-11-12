<?php
include '../../conexion.php';
include '../../funciones/notificar_usuario/notificar_usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['nuevo_estado'];
    $estado_actual = $_POST['estado_actual'];
    $correo_usuario = $_POST['correo_usuario'];

    // Actualizar el estado del pedido solo si es diferente del estado actual
    if ($estado_actual != $nuevo_estado) {
        $sql = "UPDATE pedido SET estado_pedido = ? WHERE id_pedido = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('si', $nuevo_estado, $id_pedido);

        if ($stmt->execute()) {
            // Enviar notificación automáticamente si el estado ha cambiado
            echo enviarCorreoNotificacion($id_pedido, $nuevo_estado, $correo_usuario);
            header("Location: listar.php?actualizado=1");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
    } else {
        // Redirige sin actualizar ni enviar notificación si el estado no cambió
        header("Location: listar.php?sin_cambio=1");
        exit();
    }
} else {
    header("Location: listar.php");
    exit();
}
