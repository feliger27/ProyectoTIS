<?php
// config_transbank.php
require '../../libs/vendor/autoload.php';

use Transbank\Webpay\WebpayPlus\Transaction;

// Configuración para el entorno de pruebas de Transbank
define("TBK_RETURN_URL", "http://localhost/ProyectoTis/funciones/transbank/exito.php"); // URL de redirección después del pago exitoso
define("TBK_FINAL_URL", "http://localhost/ProyectoTis/funciones/transbank/confirmacion.php"); // URL de confirmación de pago

