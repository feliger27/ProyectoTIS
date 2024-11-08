<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../libs/PHPMailer/src/Exception.php';
require '../../libs/PHPMailer/src/PHPMailer.php';
require '../../libs/PHPMailer/src/SMTP.php';

function enviarCorreoNotificacion($id_pedido, $estado_pedido, $correo)
{
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP de Mailtrap
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '02508a3a3a9bf5';
        $mail->Password = '99c3b27d1457ed';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 25;
        $mail->CharSet = 'UTF-8';

        // Configuración de los detalles del correo
        $mail->setFrom('notificaciones@tuprojecto.com', 'Tu Proyecto');
        $mail->addAddress($correo);

        $mail->isHTML(true);
        $mail->Subject = 'Actualización de Estado de Pedido';
        $mail->Body    = "El estado de su pedido con ID #$id_pedido ha cambiado a: $estado_pedido.";
        $mail->AltBody = "El estado de su pedido con ID #$id_pedido ha cambiado a: $estado_pedido.";

        $mail->send();
        return "<div class='alert alert-success'>Notificación enviada exitosamente a $correo.</div>";
    } catch (Exception $e) {
        return "<div class='alert alert-danger'>Error al enviar el mensaje: {$mail->ErrorInfo}</div>";
    }
}
?>
