<?php
// Cargar la configuración y el SDK de Transbank
require 'config_transbank.php';

use Transbank\Webpay\WebpayPlus\Transaction;

// Obtener el monto de la URL o de la sesión
$monto = isset($_GET['monto']) ? intval($_GET['monto']) : 0;

try {
    $transaction = new Transaction();
    $response = $transaction->create(
        "orden12345",                  // Identificador único de la orden
        uniqid(),                      // Código único de compra
        $monto,                        // Monto recibido dinámicamente
        TBK_RETURN_URL                 // URL de retorno después del pago
    );

    // Redirigir al usuario al formulario de pago de Transbank
    header("Location: " . $response->getUrl() . "?token_ws=" . $response->getToken());
    exit();
} catch (Exception $e) {
    echo "Error en la transacción: " . $e->getMessage();
}
