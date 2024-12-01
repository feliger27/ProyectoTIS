<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pedido = isset($_POST['id_pedido']) ? (int)$_POST['id_pedido'] : 0;

    // Verificar si el ID del pedido es válido
    if ($id_pedido > 0) {
        // Eliminar dependencias en las tablas relacionadas
        $tablas_dependientes = [
            'pedido_bebida',
            'pedido_hamburguesa',
            'pedido_combo',
            'pedido_acompaniamiento',
            'pedido_postre',
            'recompensa',
            'boleta',
            'valoracion'
        ];

        foreach ($tablas_dependientes as $tabla) {
            $sql_dependencia = "DELETE FROM $tabla WHERE id_pedido = ?";
            $stmt_dependencia = $conexion->prepare($sql_dependencia);
            $stmt_dependencia->bind_param("i", $id_pedido);
            $stmt_dependencia->execute();
        }

        // Eliminar el pedido de la tabla principal
        $sql_pedido = "DELETE FROM pedido WHERE id_pedido = ?";
        $stmt_pedido = $conexion->prepare($sql_pedido);
        $stmt_pedido->bind_param("i", $id_pedido);

        if ($stmt_pedido->execute()) {
            // Redirigir con mensaje de éxito
            header("Location: listar.php?eliminado=true&id=$id_pedido");
            exit();
        } else {
            echo "Error al eliminar el pedido: " . $conexion->error;
        }
    } else {
        echo "Error: ID del pedido no válido.";
    }
} else {
    echo "Método de solicitud no permitido.";
}
?>
