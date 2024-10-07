<?php
include '../../conexion.php';

// Verificamos que se ha pasado un ID
if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit;
}

$id_combo = $_GET['id'];

// Eliminar relaciones del combo en otras tablas
$sqlEliminarHamburguesas = "DELETE FROM combo_hamburguesa WHERE id_combo = ?";
$sqlEliminarAcompaniamientos = "DELETE FROM combo_acompaniamiento WHERE id_combo = ?";
$sqlEliminarBebidas = "DELETE FROM combo_bebida WHERE id_combo = ?";
$sqlEliminarPostres = "DELETE FROM combo_postre WHERE id_combo = ?";

// Preparamos y ejecutamos las consultas para eliminar las relaciones
$stmt = $conexion->prepare($sqlEliminarHamburguesas);
$stmt->bind_param("i", $id_combo);
$stmt->execute();

$stmt = $conexion->prepare($sqlEliminarAcompaniamientos);
$stmt->bind_param("i", $id_combo);
$stmt->execute();

$stmt = $conexion->prepare($sqlEliminarBebidas);
$stmt->bind_param("i", $id_combo);
$stmt->execute();

$stmt = $conexion->prepare($sqlEliminarPostres);
$stmt->bind_param("i", $id_combo);
$stmt->execute();

// Eliminar el combo en la tabla principal
$sqlEliminarCombo = "DELETE FROM combo WHERE id_combo = ?";
$stmt = $conexion->prepare($sqlEliminarCombo);
$stmt->bind_param("i", $id_combo);

if ($stmt->execute()) {
    // Redirigir al listado de combos si la eliminación fue exitosa
    header("Location: listar.php?mensaje=eliminado");
} else {
    echo "<div class='alert alert-danger'>Error al eliminar el combo: " . $stmt->error . "</div>";
}
?>
