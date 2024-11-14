<?php
include '../../conexion.php';

if (isset($_POST['id_promocion'])) {
    $idPromocion = $_POST['id_promocion'];

    // Validar que el ID es numérico antes de realizar la consulta
    if (is_numeric($idPromocion)) {
        $sql = "DELETE FROM promocion WHERE id_promocion = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idPromocion);

        if ($stmt->execute()) {
            header("Location: listar.php?eliminado=true&id={$idPromocion}");
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Error al eliminar la promoción."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "ID de promoción inválido."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID de promoción no proporcionado."]);
}

$conexion->close();
?>


