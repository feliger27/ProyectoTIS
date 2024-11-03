<?php
// confirmacion.php
$response = $_POST; // Obtener respuesta de Transbank
file_put_contents("log.txt", json_encode($response), FILE_APPEND); // Guardar en un log para revisión
echo "Pago confirmado. Estado: " . $response['status'];
?>
