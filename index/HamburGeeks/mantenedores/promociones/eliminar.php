<?php
include '../../conexion.php'; 

// Verificamos que se ha pasado un ID
if (!isset($_POST['id_promocion'])) {
    header("Location: listar.php");
    exit;
}

$id_promocion = $_POST['id_promocion'];

// Si se está realizando una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "DELETE FROM promocion WHERE id_promocion = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_promocion);
    if ($stmt->execute()) {
        // Redirigir a listar.php con mensaje de éxito y ID de la promoción
        header("Location: listar.php?eliminado=1&id=" . $id_promocion);
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
    }
}
?>
