<?php
// config_transbank.php
require '../vendor/autoload.php'; // Cargar el SDK de Transbank

use Transbank\Webpay\WebpayPlus\Transaction;

// Configuración para el entorno de pruebas de Transbank
define("TBK_RETURN_URL", "http://localhost/ProyectoTis/transbank/exito.php"); // URL de redirección después del pago exitoso
define("TBK_FINAL_URL", "http://localhost/ProyectoTis/transbank/confirmacion.php"); // URL de confirmación de pago
