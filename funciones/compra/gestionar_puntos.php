<?php
// Función para gestionar puntos de recompensa
function gestionarPuntos($conexion, $idUsuario, $idPedido, $totalCompra, $puntosUsados) {
    // 1. Calcular los nuevos puntos
    $nuevosPuntos = floor(($totalCompra - $puntosUsados) * 0.05);

    // Obtener los puntos actuales del usuario
    $queryUsuario = "SELECT puntos_recompensa FROM usuario WHERE id_usuario = ?";
    $stmtUsuario = $conexion->prepare($queryUsuario);
    $stmtUsuario->bind_param("i", $idUsuario);
    $stmtUsuario->execute();
    $resultado = $stmtUsuario->get_result();

    if ($resultado->num_rows === 0) {
        throw new Exception("Usuario no encontrado.");
    }

    $usuario = $resultado->fetch_assoc();
    $puntosActuales = $usuario['puntos_recompensa'];

    // Verificar si los puntos usados son válidos
    if ($puntosUsados > $puntosActuales) {
        throw new Exception("Puntos utilizados exceden los puntos disponibles.");
    }

    // Restar los puntos utilizados y sumar los nuevos puntos
    $puntosActualizados = $puntosActuales - $puntosUsados + $nuevosPuntos;

    // Actualizar los puntos del usuario
    $queryActualizarUsuario = "UPDATE usuario SET puntos_recompensa = ? WHERE id_usuario = ?";
    $stmtActualizarUsuario = $conexion->prepare($queryActualizarUsuario);
    $stmtActualizarUsuario->bind_param("ii", $puntosActualizados, $idUsuario);
    $stmtActualizarUsuario->execute();

    // Registrar los puntos utilizados en el pedido
    $queryActualizarPedido = "UPDATE pedido SET puntos_utilizados = ? WHERE id_pedido = ?";
    $stmtActualizarPedido = $conexion->prepare($queryActualizarPedido);
    $stmtActualizarPedido->bind_param("ii", $puntosUsados, $idPedido);
    $stmtActualizarPedido->execute();

    // Confirmación en logs
    error_log("Puntos gestionados correctamente: Usuario $idUsuario, Pedido $idPedido, Nuevos Puntos $nuevosPuntos.");

    return true;
}
?>