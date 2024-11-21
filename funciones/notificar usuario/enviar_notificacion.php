<?php
require '../../libs/PHPMailer/src/Exception.php';
require '../../libs/PHPMailer/src/PHPMailer.php';
require '../../libs/PHPMailer/src/SMTP.php';

function enviarCorreoNotificacion($id_pedido, $estado_pedido, $correo)
{
    // Mapeo de estados a texto legible
    $estado_legible = [
        'en_preparacion' => 'En Preparación',
        'en_reparto' => 'En Reparto',
        'entregado' => 'Entregado'
    ];

    // Obtener el texto legible del estado
    $estado_actual_legible = $estado_legible[$estado_pedido] ?? $estado_pedido;

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '02508a3a3a9bf5';
        $mail->Password = '99c3b27d1457ed';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;
        $mail->CharSet = 'UTF-8';

        // Configurar correo de origen y destinatario
        $mail->setFrom('from@example.com', 'Tu Proyecto');
        $mail->addAddress($correo);

        // Contenido del correo (HTML con Bootstrap)
        $mail->isHTML(true);
        $mail->Subject = 'Seguimiento de Pedido';

        // Cuerpo del correo con el diseño
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4;'>
            <h2 style='text-align: center; color: #333;'>Seguimiento de Pedido</h2>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);'>
                <div style='display: flex; justify-content: space-between; text-align: center;'>
                    <div style='flex: 1; padding: 10px; background-color: " . ($estado_pedido === 'en_preparacion' ? '#ffc107' : '#f8f9fa') . "; border-radius: 5px;'>
                        <h4 style='margin: 0; color: " . ($estado_pedido === 'en_preparacion' ? '#fff' : '#333') . ";'>En Preparación</h4>
                    </div>
                    <div style='flex: 1; padding: 10px; background-color: " . ($estado_pedido === 'en_reparto' ? '#ffc107' : '#f8f9fa') . "; border-radius: 5px;'>
                        <h4 style='margin: 0; color: " . ($estado_pedido === 'en_reparto' ? '#fff' : '#333') . ";'>En Reparto</h4>
                    </div>
                    <div style='flex: 1; padding: 10px; background-color: " . ($estado_pedido === 'entregado' ? '#ffc107' : '#f8f9fa') . "; border-radius: 5px;'>
                        <h4 style='margin: 0; color: " . ($estado_pedido === 'entregado' ? '#fff' : '#333') . ";'>Entregado</h4>
                    </div>
                </div>
                <p style='text-align: center; margin-top: 20px; color: #555;'>
                    Pedido ID: <strong>#{$id_pedido}</strong><br>
                    Estado Actual: <strong>{$estado_actual_legible}</strong>
                </p>
            </div>
        </div>
        ";

        // Alternativo en caso de que el cliente no soporte HTML
        $mail->AltBody = "El estado de su pedido con ID #$id_pedido ha cambiado a: $estado_actual_legible.";

        // Enviar correo
        if ($mail->send()) {
            return true; // Retorna éxito
        } else {
            return $mail->ErrorInfo; // Retorna error si falla
        }
    } catch (Exception $e) {
        return "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
}
?>
