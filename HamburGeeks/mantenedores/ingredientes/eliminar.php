<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ingrediente = $_POST['id_ingrediente'];

    // Verificar si el ID del ingrediente está presente
    if (!empty($id_ingrediente)) {
        // Preparar la consulta para eliminar el ingrediente
        $sql = "DELETE FROM ingrediente WHERE id_ingrediente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_ingrediente); // "i" para int

        if ($stmt->execute()) {
            // Redirigir a la lista de ingredientes con un mensaje de éxito
            header("Location: listar.php?eliminado=true&id=$id_ingrediente");
            exit();
        } else {
            echo "Error al eliminar el ingrediente: " . $conexion->error;
        }
    }
}
?>
