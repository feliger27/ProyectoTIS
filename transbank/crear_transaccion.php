<?php
// Cargar la configuración y el SDK de Transbank
require 'config_transbank.php';

use Transbank\Webpay\WebpayPlus\Transaction;

// Crear una transacción de prueba
try {
    $transaction = new Transaction();
    $response = $transaction->create(
        "orden12345",                  // Identificador único de la orden
        uniqid(),                      // Código único de compra
        1000,                          // Monto de prueba en pesos chilenos
        TBK_RETURN_URL                 // URL de retorno después del pago
    );

    // Redirigir al usuario al formulario de pago de Transbank
    header("Location: " . $response->getUrl() . "?token_ws=" . $response->getToken());
    exit();
} catch (Exception $e) {
    echo "Error en la transacción: " . $e->getMessage();
}
