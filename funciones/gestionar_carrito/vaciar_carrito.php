<?php
session_start();

// Vaciar el carrito
unset($_SESSION['carrito']);

// Responder con éxito
echo json_encode(['success' => 'Carrito vaciado correctamente.']);
?>