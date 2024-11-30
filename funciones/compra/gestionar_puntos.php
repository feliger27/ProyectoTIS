<?php
// Función para gestionar puntos de recompensa
function gestionarPuntos($conexion, $idUsuario, $totalCompra, $puntosUsados) {
    // 1. Calcular los nuevos puntos que se sumarán al usuario
    // Se calcula el 5% del total de la compra menos los puntos utilizados
    $nuevosPuntos = floor(($totalCompra - $puntosUsados) * 0.05);

    // Verificar si el usuario tiene puntos disponibles
    $queryUsuario = "SELECT puntos_recompensa FROM usuario WHERE id_usuario = ?";
    $stmt = $conexion->prepare($queryUsuario);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        die("Usuario no encontrado.");
    }

    $usuario = $resultado->fetch_assoc();
    $puntosExistentes = $usuario['puntos_recompensa'] ?? 0;

    // 2. Actualizar los puntos de recompensa del usuario
    // Se suman los nuevos puntos a los puntos existentes
    $puntosTotales = $puntosExistentes + $nuevosPuntos;

    // Consulta para actualizar los puntos del usuario
    $queryActualizarPuntos = "UPDATE usuario SET puntos_recompensa = ? WHERE id_usuario = ?";
    $stmtActualizar = $conexion->prepare($queryActualizarPuntos);

    if (!$stmtActualizar) {
        die("Error al preparar la consulta de actualización de puntos: " . $conexion->error);
    }

    $stmtActualizar->bind_param("ii", $puntosTotales, $idUsuario);

    if (!$stmtActualizar->execute()) {
        die("Error al actualizar los puntos: " . $stmtActualizar->error);
    }

    // Confirmación en el log
    error_log("Puntos de recompensa actualizados para el usuario $idUsuario: $puntosTotales puntos.");
    
    // Devuelve los puntos totales actualizados
    return $puntosTotales;  
}
?>