<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../libs/PHPMailer/src/Exception.php';
require '../../libs/PHPMailer/src/PHPMailer.php';
require '../../libs/PHPMailer/src/SMTP.php';
/**
 * Configuración básica para PHPMailer
 *
 * @return PHPMailer Configuración inicializada
 */
function configurarCorreo()
{
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '02508a3a3a9bf5'; // Usuario de Mailtrap
        $mail->Password = '99c3b27d1457ed'; // Contraseña de Mailtrap
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 25;
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('notificaciones@tuprojecto.com', 'Tu Proyecto'); // Remitente
    } catch (Exception $e) {
        echo "Error en configuración: {$mail->ErrorInfo}";
    }
    return $mail;
}
?>
