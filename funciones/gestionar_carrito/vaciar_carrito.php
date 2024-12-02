<?php
session_start();

if (isset($_SESSION['carrito'])) {
    unset($_SESSION['carrito']);
    echo json_encode(['status' => 'success', 'message' => 'Carrito vaciado correctamente.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'El carrito ya está vacío.']);
}
exit;
?>
