<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_acompaniamiento = $_POST['id_acompaniamiento'];

    $sql = "DELETE FROM acompaniamiento WHERE id_acompaniamiento = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_acompaniamiento);
    if ($stmt->execute()) {
        // Redirigir a listar.php con mensaje de éxito y ID del acompaniamiento
        header("Location: listar.php?eliminado=1&id=" . $id_acompaniamiento);
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
    }
}
?>