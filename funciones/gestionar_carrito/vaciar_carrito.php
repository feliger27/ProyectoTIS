<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vaciar el carrito
unset($_SESSION['carrito']);

echo json_encode([
    'status' => 'success',
    'message' => 'El carrito se ha vaciado exitosamente.'
]);