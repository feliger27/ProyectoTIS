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

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hamburgeeks";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        return "Error de conexión a la base de datos: " . $conn->connect_error;
    }

    // Consultar datos del pedido
    $sql_pedido = "SELECT p.id_pedido, p.fecha_pedido, p.monto, u.nombre, u.apellido
                   FROM pedido p
                   JOIN usuario u ON p.id_usuario = u.id_usuario
                   WHERE p.id_pedido = $id_pedido";
    $result_pedido = $conn->query($sql_pedido);

    if ($result_pedido->num_rows > 0) {
        $pedido = $result_pedido->fetch_assoc();
        $nombre_cliente = $pedido['nombre'] . " " . $pedido['apellido'];
        $fecha_pedido = date("d-m-Y - H:i A", strtotime($pedido['fecha_pedido']));
        $total = number_format($pedido['monto'], 0, ',', '.');
    } else {
        return "No se encontró el pedido.";
    }

    // Consultar detalle del pedido
    $sql_detalle = "SELECT 'Hamburguesa' AS tipo, h.nombre_hamburguesa AS detalle, ph.cantidad, ph.precio
                    FROM pedido_hamburguesa ph
                    JOIN hamburguesa h ON ph.id_hamburguesa = h.id_hamburguesa
                    WHERE ph.id_pedido = $id_pedido
                    UNION ALL
                    SELECT 'Bebida' AS tipo, b.nombre_bebida, pb.cantidad, pb.precio
                    FROM pedido_bebida pb
                    JOIN bebida b ON pb.id_bebida = b.id_bebida
                    WHERE pb.id_pedido = $id_pedido
                    UNION ALL
                    SELECT 'Acompañamiento' AS tipo, a.nombre_acompaniamiento, pa.cantidad, pa.precio
                    FROM pedido_acompaniamiento pa
                    JOIN acompaniamiento a ON pa.id_acompaniamiento = a.id_acompaniamiento
                    WHERE pa.id_pedido = $id_pedido";
    $result_detalle = $conn->query($sql_detalle);

    $detalle_pedido_html = "";
    while ($detalle = $result_detalle->fetch_assoc()) {
        $detalle_pedido_html .= "
            <tr>
                <td style='padding: 10px; text-align: center; border: 1px solid #ddd;'>{$detalle['cantidad']}</td>
                <td style='padding: 10px; border: 1px solid #ddd;'>{$detalle['detalle']}</td>
                <td style='padding: 10px; text-align: right; border: 1px solid #ddd;'>$" . number_format($detalle['precio'], 0, ',', '.') . "</td>
            </tr>
        ";
    }

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

        // Contenido del correo (HTML con el diseño del formulario)
        $mail->isHTML(true);
        $mail->Subject = 'Resumen y Seguimiento de Pedido';

        // Cuerpo del correo
        $mail->Body = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Resumen del Pedido</title>
        </head>
        <body style='font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;'>
            <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border-collapse: collapse; background-color: #ffffff; border: 1px solid #dddddd; margin-top: 20px;'>
                <tr>
                    <td align='center' style='padding: 20px; background-color: #ffc107; color: #333; font-size: 20px; font-weight: bold;'>
                        RESUMEN DEL PEDIDO 🍔
                    </td>
                </tr>
                <tr>
                    <td style='padding: 20px; text-align: center; color: #555; border-bottom: 1px solid #dddddd;'>
                        ¡Gracias por preferir <strong>Hamburgeeks</strong>! 🍔<br>
                        Si tienes alguna pregunta sobre tu pedido, contáctanos a través de nuestro soporte al cliente:<br>
                        <strong>Email:</strong> soporte@hamburgeeks.com
                    </td>
                </tr>
                <tr>
                    <td style='padding: 20px; border-bottom: 1px solid #dddddd;'>
                        <h4 style='color: #007bff; margin: 0;'>Información del Cliente</h4>
                        <hr style='border: none; border-top: 1px solid #dddddd; margin: 10px 0;'>
                        <p><strong>Nombre:</strong> {$nombre_cliente}</p>
                        <p><strong>Orden:</strong> #{$id_pedido}</p>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 20px; border-bottom: 1px solid #dddddd;'>
                        <h4 style='color: #007bff; margin: 0;'>Seguimiento del Pedido</h4>
                        <hr style='border: none; border-top: 1px solid #dddddd; margin: 10px 0;'>
                        <p>Revisa aquí el estado de tu pedido:</p>
                        <a href='https://hamburgeeks.com/seguimiento' style='color: #ffc107; text-decoration: none;'>https://hamburgeeks.com/seguimiento</a>
                        <p><strong>Fecha del Pedido:</strong> {$fecha_pedido}</p>
                        <p><strong>Estado Actual:</strong> {$estado_actual_legible}</p>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 20px;'>
                        <h4 style='color: #007bff; margin: 0;'>Detalle del Pedido</h4>
                        <hr style='border: none; border-top: 1px solid #dddddd; margin: 10px 0;'>
                        <table width='100%' style='border-collapse: collapse; margin-top: 10px;'>
                            <thead>
                                <tr style='background-color: #f1f1f1; text-align: center;'>
                                    <th style='padding: 10px; border: 1px solid #ddd;'>Cantidad</th>
                                    <th style='padding: 10px; border: 1px solid #ddd;'>Detalle</th>
                                    <th style='padding: 10px; border: 1px solid #ddd;'>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$detalle_pedido_html}
                            </tbody>
                        </table>
                        <p style='text-align: right; margin-top: 10px;'><strong>Total:</strong> $ {$total}</p>
                    </td>
                </tr>
            </table>
        </body>
        </html>
        ";

        // Enviar correo
        if ($mail->send()) {
            return true; // Retorna éxito
        } else {
            return $mail->ErrorInfo; // Retorna error si falla
        }
    } catch (Exception $e) {
        return "Error al enviar el mensaje: {$mail->ErrorInfo}";
    } finally {
        $conn->close();
    }
}
