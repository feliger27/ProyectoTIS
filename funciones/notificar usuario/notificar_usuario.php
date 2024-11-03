<?php
require '../../libs/PHPMailer/src/Exception.php';
require '../../libs/PHPMailer/src/PHPMailer.php';
require '../../libs/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pedido = $_POST['id_pedido'];
    $estado_pedido = $_POST['estado_pedido'];
    $correoSeleccionado = $_POST['correo'];
    $nuevoCorreo = $_POST['nuevo_correo'];

    $correoDestino = $nuevoCorreo ?: $correoSeleccionado;

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';  // Cambiar por el servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'YOUR_USERNAME';
        $mail->Password = 'YOUR_PASSWORD';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('from@example.com', 'Tu Proyecto');
        $mail->addAddress($correoDestino);

        $mail->isHTML(true);
        $mail->Subject = 'Actualización de Estado del Pedido';
        $mail->Body    = "El estado de su pedido con ID #$id_pedido ha sido actualizado a: $estado_pedido.";
        $mail->AltBody = "El estado de su pedido con ID #$id_pedido ha sido actualizado a: $estado_pedido.";

        $mail->send();
        echo 'La notificación ha sido enviada.';
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
}
?>
