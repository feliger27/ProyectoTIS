<?php
session_start();

// Vaciar el carrito
unset($_SESSION['carrito']);

// Redirigir de vuelta al carrito
header('Location: ../../index/index-carrito.php');
exit;
?>