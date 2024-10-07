<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pedido = $_POST['id_pedido'];

    // Verificar si el ID del pedido está presente
    if (!empty($id_pedido)) {
        // Eliminar dependencias en la tabla pedido_bebida
        $sql_dependencias = "DELETE FROM pedido_bebida WHERE id_pedido = ?";
        $stmt_dependencias = $conexion->prepare($sql_dependencias);
        $stmt_dependencias->bind_param("i", $id_pedido);
        $stmt_dependencias->execute();

        // Eliminar dependencias en la tabla pedido_hamburguesa
        $sql_dependencias = "DELETE FROM pedido_hamburguesa WHERE id_pedido = ?";
        $stmt_dependencias = $conexion->prepare($sql_dependencias);
        $stmt_dependencias->bind_param("i", $id_pedido);
        $stmt_dependencias->execute();

        // Eliminar dependencias en la tabla pedido_combo
        $sql_dependencias = "DELETE FROM pedido_combo WHERE id_pedido = ?";
        $stmt_dependencias = $conexion->prepare($sql_dependencias);
        $stmt_dependencias->bind_param("i", $id_pedido);
        $stmt_dependencias->execute();

        // Eliminar dependencias en la tabla pedido_acompaniamiento
        $sql_dependencias = "DELETE FROM pedido_acompaniamiento WHERE id_pedido = ?";
        $stmt_dependencias = $conexion->prepare($sql_dependencias);
        $stmt_dependencias->bind_param("i", $id_pedido);
        $stmt_dependencias->execute();

        // Eliminar dependencias en la tabla pedido_postre
        $sql_dependencias = "DELETE FROM pedido_postre WHERE id_pedido = ?";
        $stmt_dependencias = $conexion->prepare($sql_dependencias);
        $stmt_dependencias->bind_param("i", $id_pedido);
        $stmt_dependencias->execute();

        // Eliminar dependencias en la tabla recompensa
        $sql_dependencias = "DELETE FROM recompensa WHERE id_pedido = ?";
        $stmt_dependencias = $conexion->prepare($sql_dependencias);
        $stmt_dependencias->bind_param("i", $id_pedido);
        $stmt_dependencias->execute();

        // Eliminar dependencias en la tabla boleta
        $sql_dependencias = "DELETE FROM boleta WHERE id_pedido = ?";
        $stmt_dependencias = $conexion->prepare($sql_dependencias);
        $stmt_dependencias->bind_param("i", $id_pedido);
        $stmt_dependencias->execute();

        // Eliminar dependencias en la tabla valoracion
        $sql_dependencias = "DELETE FROM valoracion WHERE id_pedido = ?";
        $stmt_dependencias = $conexion->prepare($sql_dependencias);
        $stmt_dependencias->bind_param("i", $id_pedido);
        $stmt_dependencias->execute();

        // Ahora eliminar el pedido
        $sql = "DELETE FROM pedido WHERE id_pedido = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_pedido);

        if ($stmt->execute()) {
            // Redirigir a la lista de pedidos con un mensaje de éxito
            header("Location: listar.php?eliminado=true&id=$id_pedido");
            exit();
        } else {
            echo "Error al eliminar el pedido: " . $conexion->error;
        }
    }
}
?>


