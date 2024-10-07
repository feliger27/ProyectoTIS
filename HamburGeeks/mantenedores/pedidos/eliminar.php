<?php
include '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'];

    // Eliminar el pedido
    $sql = "DELETE FROM pedido WHERE id_pedido = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('i', $id_pedido);

    if ($stmt->execute()) {
        header('Location: listar.php?eliminado=1&id=' . $id_pedido);
        exit();
    } else {
        echo "Error al eliminar el pedido.";
    }
} else {
    header('Location: listar.php');
    exit();
}
?>
