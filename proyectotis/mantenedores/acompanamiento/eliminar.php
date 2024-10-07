<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_acompanamiento = $_POST['id_acompanamiento'];

    $sql = "DELETE FROM acompanamiento WHERE id_acompanamiento = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_acompanamiento);
    if ($stmt->execute()) {
        // Redirigir a listar.php con mensaje de éxito y ID del acompanamiento
        header("Location: listar.php?eliminado=1&id=" . $id_acompanamiento);
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
    }
}
?>