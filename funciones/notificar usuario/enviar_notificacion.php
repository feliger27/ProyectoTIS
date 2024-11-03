<?php
require '../../libs/PHPMailer/src/Exception.php';
require '../../libs/PHPMailer/src/PHPMailer.php';
require '../../libs/PHPMailer/src/SMTP.php';

function enviarCorreoNotificacion($id_pedido, $estado_pedido, $correo)
{
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '638fc0feb5b286';
        $mail->Password = 'd94b23860dbbd4';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 25;
        $mail->CharSet = 'UTF-8';

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
