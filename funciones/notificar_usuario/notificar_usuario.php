<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer/src/Exception.php';
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';


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
// Función para enviar correo de restablecimiento de contraseña
function enviarCorreoRestablecimiento($email, $token) {
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io'; // Cambia a tu servidor SMTP real
        $mail->SMTPAuth = true;
        $mail->Username = '02508a3a3a9bf5';
        $mail->Password = '99c3b27d1457ed';  // Tu contraseña de SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 25;
        $mail->CharSet = 'UTF-8';

        // Configuración de los detalles del correo
        $mail->setFrom('notificaciones@tuprojecto.com', 'Tu Proyecto');
        $mail->addAddress($email);

        // Establecer el contenido del correo
        $reset_link = "http://http://localhost/xampp/hamburgeeks/ProyectoTIS/login/restablecer.php?token=" . $token;
        $mail->isHTML(true);
        $mail->Subject = 'Restablecimiento de Contraseña';
        $mail->Body    = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='$reset_link'>$reset_link</a>";
        $mail->AltBody = "Haz clic en el siguiente enlace para restablecer tu contraseña: $reset_link";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
