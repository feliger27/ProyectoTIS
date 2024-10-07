<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_postre = $_POST['id_postre'];

    $sql = "DELETE FROM postre WHERE id_postre = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_postre);
    if ($stmt->execute()) {
        // Redirigir a listar.php con mensaje de éxito y ID del postre
        header("Location: listar.php?eliminado=1&id=" . $id_postre);
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
    }
}
?>
