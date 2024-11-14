<?php
include '../../conexion.php';

$id = $_POST['id'] ?? null;
$tipo = $_POST['tipo'] ?? null;
$cantidad = $_POST['cantidad'] ?? null;
$umbral = $_POST['umbral'] ?? null;

if (!$id || !$tipo || $cantidad === null || $umbral === null) {
    echo "Faltan datos para realizar la actualización.";
    exit();
}

// Determina la tabla y la columna de acuerdo al tipo de producto
if ($tipo === 'ingrediente') {
    $tabla = 'ingrediente';
    $columna_cantidad = 'cantidad';
    $columna_umbral = 'umbral_reabastecimiento';
} elseif ($tipo === 'postre') {
    $tabla = 'postre';
    $columna_cantidad = 'cantidad';
    $columna_umbral = 'umbral_reabastecimiento';
} elseif ($tipo === 'acompaniamiento') {
    $tabla = 'acompaniamiento';
    $columna_cantidad = 'cantidad';
    $columna_umbral = 'umbral_reabastecimiento';
} elseif ($tipo === 'bebida') {
    $tabla = 'bebida';
    $columna_cantidad = 'cantidad';
    $columna_umbral = 'umbral_reabastecimiento';
} elseif ($tipo === 'aderezo') {
    $tabla = 'aderezo';
    $columna_cantidad = 'cantidad';
    $columna_umbral = 'umbral_reabastecimiento';
} else {
    echo "Tipo de producto no reconocido.";
    exit();
}

// Actualiza el stock y el umbral en la base de datos
$query = "UPDATE $tabla SET $columna_cantidad = ?, $columna_umbral = ? WHERE id_$tipo = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("dii", $cantidad, $umbral, $id);
$stmt->execute();
$stmt->close();

//echo "Stock actualizado correctamente.";
?>





