<?php
include '../../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_aderezo = $_POST['id_aderezo'];

    $sql = "DELETE FROM aderezo WHERE id_aderezo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_aderezo);
    if ($stmt->execute()) {
        // Redirigir a listar.php con mensaje de éxito y ID del aderezo
        header("Location: listar.php?eliminado=1&id=" . $id_aderezo);
        exit;
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
    }
}
?>
