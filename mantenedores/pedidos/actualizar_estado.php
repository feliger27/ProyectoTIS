<?php
include '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pedido = $_POST['id_pedido'];
    $nuevo_estado = $_POST['nuevo_estado'];

    // Actualizar el estado del pedido
    $sql = "UPDATE pedido SET estado_pedido = ? WHERE id_pedido = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('si', $nuevo_estado, $id_pedido);

    if ($stmt->execute()) {
        header("Location: listar.php?actualizado=1");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
} else {
    header("Location: listar.php");
    exit();
}

