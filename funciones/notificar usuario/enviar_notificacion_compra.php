<?php
require_once('configurar_correo.php');
require_once('../../conexion.php');
/**
 * Enviar correo de notificación de pedido con diseño detallado
 *
 * @param int $id_pedido ID del pedido
 * @param string $estado_pedido Estado del pedido
 * @param string $correo Correo del destinatario
 * @return string Mensaje de éxito o error
 */
function enviarCorreoNotificacion($id_pedido, $estado_pedido, $correo)
{
    // Mapeo de estados a texto legible
    global $conexion; // Asegúrate de usar la conexión global

    $estado_legible = [
        'en_preparacion' => 'En Preparación',
        'en_reparto' => 'En Reparto',
        'entregado' => 'Entregado'
    ];
    $estado_actual_legible = $estado_legible[$estado_pedido] ?? $estado_pedido;


    // Obtener datos del pedido
    $sql_pedido = "SELECT p.id_pedido, p.fecha_pedido, p.monto, u.nombre, u.apellido
                   FROM pedido p
                   JOIN usuario u ON p.id_usuario = u.id_usuario
                   WHERE p.id_pedido = $id_pedido";
    $result_pedido = $conexion->query($sql_pedido);

    if ($result_pedido->num_rows > 0) {
        $pedido = $result_pedido->fetch_assoc();
        $nombre_cliente = $pedido['nombre'] . " " . $pedido['apellido'];
        $fecha_pedido = date("d-m-Y - H:i A", strtotime($pedido['fecha_pedido']));
        $total = number_format($pedido['monto'], 0, ',', '.');
    } else {
        return "No se encontró el pedido.";
    }

    // Obtener detalle del pedido
    $sql_detalle = "SELECT 'Hamburguesa' AS tipo, h.nombre_hamburguesa AS detalle, ph.cantidad, ph.precio
                    FROM pedido_hamburguesa ph
                    JOIN hamburguesa h ON ph.id_hamburguesa = h.id_hamburguesa
                    WHERE ph.id_pedido = $id_pedido
                    UNION ALL
                    SELECT 'Bebida' AS tipo, b.nombre_bebida AS detalle, pb.cantidad, pb.precio
                    FROM pedido_bebida pb
                    JOIN bebida b ON pb.id_bebida = b.id_bebida
                    WHERE pb.id_pedido = $id_pedido
                    UNION ALL
                    SELECT 'Acompañamiento' AS tipo, a.nombre_acompaniamiento AS detalle, pa.cantidad, pa.precio
                    FROM pedido_acompaniamiento pa
                    JOIN acompaniamiento a ON pa.id_acompaniamiento = a.id_acompaniamiento
                    WHERE pa.id_pedido = $id_pedido";
    $result_detalle = $conexion->query($sql_detalle);

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

    $mail = configurarCorreo();
    try {
        $mail->addAddress($correo);

        // Configurar contenido del correo
        $mail->isHTML(true);
        
        $mail->Subject = 'Resumen y Seguimiento de Pedido';
        // Definir el HTML para los estados del pedido
        $estados_html = "
        <div style='display: flex; justify-content: space-between; align-items: center; margin: 20px 0;'>
            <div style='flex: 1; text-align: center; padding: 10px; font-weight: " . ($estado_pedido === 'en_preparacion' ? "bold" : "normal") . "; 
                        background-color: " . ($estado_pedido === 'en_preparacion' ? "#ffc107" : "#f9f9f9") . "; 
                        color: " . ($estado_pedido === 'en_preparacion' ? "#fff" : "#555") . "; 
                        border: 1px solid #ddd; border-radius: 5px; margin: 0 5px;'>
                En Preparación
            </div>
            <div style='flex: 1; text-align: center; padding: 10px; font-weight: " . ($estado_pedido === 'en_reparto' ? "bold" : "normal") . "; 
                        background-color: " . ($estado_pedido === 'en_reparto' ? "#007bff" : "#f9f9f9") . "; 
                        color: " . ($estado_pedido === 'en_reparto' ? "#fff" : "#555") . "; 
                        border: 1px solid #ddd; border-radius: 5px; margin: 0 5px;'>
                En Reparto
            </div>
            <div style='flex: 1; text-align: center; padding: 10px; font-weight: " . ($estado_pedido === 'entregado' ? "bold" : "normal") . "; 
                        background-color: " . ($estado_pedido === 'entregado' ? "#28a745" : "#f9f9f9") . "; 
                        color: " . ($estado_pedido === 'entregado' ? "#fff" : "#555") . "; 
                        border: 1px solid #ddd; border-radius: 5px; margin: 0 5px;'>
                Entregado
            </div>
        </div>";
        
        

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
                        <p><strong>Nombre:</strong> {$nombre_cliente}</p>
                        <p><strong>Orden:</strong> #{$id_pedido}</p>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 20px; border-bottom: 1px solid #dddddd;'>
                        <h4 style='color: #007bff; margin: 0;'>Seguimiento del Pedido</h4>
                        <p>Estado de tu pedido:</p>
                        {$estados_html}
                        <p><strong>Fecha del Pedido:</strong> {$fecha_pedido}</p>
                    </td>
                </tr>
                <tr>
                    <td style='padding: 20px;'>
                        <h4 style='color: #007bff; margin: 0;'>Detalle del Pedido</h4>
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
                        <p style='text-align: right;'><strong>Total:</strong> $ {$total}</p>
                    </td>
                </tr>
            </table>
        </body>
        </html>";
        
        
        
        

        $mail->send();
        return "<div class='alert alert-success'>Notificación enviada exitosamente a $correo.</div>";
    } catch (Exception $e) {
        return "<div class='alert alert-danger'>Error al enviar el mensaje: {$mail->ErrorInfo}</div>";
    }
}
?>
