<?php
include '../../conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar datos del formulario
    $id_pedido = intval($_POST['id_pedido']);
    $user_id = intval($_POST['user_id']);
    $cantidad_estrellas = intval($_POST['cantidad_estrellas']);
    $comentario = trim($_POST['comentario']);

    // Validación de datos obligatorios
    if (empty($id_pedido) || empty($user_id) || empty($cantidad_estrellas) || empty($comentario)) {
        $_SESSION['mensaje_error'] = 'Todos los campos son obligatorios.';
        header("Location: ../../index/index-perfil.php");
        exit();
    }

    try {
        // Verificar que el id_pedido existe en la tabla `pedido`
        $query_verificar = "SELECT COUNT(*) AS total FROM pedido WHERE id_pedido = ?";
        $stmt_verificar = $conexion->prepare($query_verificar);
        $stmt_verificar->bind_param("i", $id_pedido);
        $stmt_verificar->execute();
        $resultado = $stmt_verificar->get_result();
        $datos = $resultado->fetch_assoc();

        if ($datos['total'] == 0) {
            $_SESSION['mensaje_error'] = "El ID de pedido no existe.";
            header("Location: ../../index/index-perfil.php");
            exit();
        }

        // Consultar si el pedido tiene combos, hamburguesas, bebidas, etc.
        $query_elementos = "
            SELECT 
                (SELECT id_combo FROM pedido_combo WHERE id_pedido = ? LIMIT 1) AS id_combo,
                (SELECT id_hamburguesa FROM pedido_hamburguesa WHERE id_pedido = ? LIMIT 1) AS id_hamburguesa,
                (SELECT id_bebida FROM pedido_bebida WHERE id_pedido = ? LIMIT 1) AS id_bebida,
                (SELECT id_postre FROM pedido_postre WHERE id_pedido = ? LIMIT 1) AS id_postre,
                (SELECT id_acompaniamiento FROM pedido_acompaniamiento WHERE id_pedido = ? LIMIT 1) AS id_acompaniamiento
        ";
        $stmt_elementos = $conexion->prepare($query_elementos);
        $stmt_elementos->bind_param("iiiii", $id_pedido, $id_pedido, $id_pedido, $id_pedido, $id_pedido);
        $stmt_elementos->execute();
        $elementos = $stmt_elementos->get_result()->fetch_assoc();

        // Extraer los elementos relacionados
        $id_combo = $elementos['id_combo'] ?? null;
        $id_hamburguesa = $elementos['id_hamburguesa'] ?? null;
        $id_bebida = $elementos['id_bebida'] ?? null;
        $id_postre = $elementos['id_postre'] ?? null;
        $id_acompaniamiento = $elementos['id_acompaniamiento'] ?? null;

        // Preparar consulta SQL para insertar en `valoracion`
        $query_insertar = "
            INSERT INTO valoracion (
                id_pedido, id_usuario, cantidad_estrellas, comentario, fecha_valoracion,
                id_combo, id_hamburguesa, id_bebida, id_postre, id_acompaniamiento
            ) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)
        ";
        $stmt_insertar = $conexion->prepare($query_insertar);

        if (!$stmt_insertar) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }

        // Vincular parámetros
        $stmt_insertar->bind_param(
            "iiisiiiii",
            $id_pedido, $user_id, $cantidad_estrellas, $comentario,
            $id_combo, $id_hamburguesa, $id_bebida, $id_postre, $id_acompaniamiento
        );

        // Ejecutar la consulta
        if (!$stmt_insertar->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt_insertar->error);
        }

        // Confirmar éxito
        $_SESSION['mensaje_exito'] = '¡Valoración registrada con éxito!';
        header("Location: ../../index/index-perfil.php");
        exit();

    } catch (Exception $e) {
        // Registrar errores en un archivo log
        error_log($e->getMessage());
        $_SESSION['mensaje_error'] = "Error al registrar la valoración. Consulte al administrador.";
        header("Location: ../../index/index-perfil.php");
        exit();
    } finally {
        // Cerrar las conexiones
        if (isset($stmt_verificar)) $stmt_verificar->close();
        if (isset($stmt_elementos)) $stmt_elementos->close();
        if (isset($stmt_insertar)) $stmt_insertar->close();
        $conexion->close();
    }
} else {
    $_SESSION['mensaje_error'] = 'Método no permitido.';
    header("Location: ../../index/index-perfil.php");
    exit();
}
