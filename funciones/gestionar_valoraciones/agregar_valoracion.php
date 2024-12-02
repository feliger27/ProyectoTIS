<?php
include '../../conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar y limpiar los datos recibidos
    $id_pedido = isset($_POST['id_pedido']) ? intval($_POST['id_pedido']) : null;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $cantidad_estrellas = isset($_POST['cantidad_estrellas']) ? intval($_POST['cantidad_estrellas']) : null;
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : null;

    // Validar datos
    if (empty($id_pedido) || empty($user_id) || empty($cantidad_estrellas) || empty($comentario)) {
        $_SESSION['mensaje_error'] = 'Todos los campos son obligatorios.';
        header("Location: ../../index/index-perfil.php");
        exit();
    }

    // Depuración: Registrar los datos que se intentan guardar
    error_log("Datos a insertar: id_pedido=$id_pedido, user_id=$user_id, estrellas=$cantidad_estrellas, comentario=$comentario");

    try {
        // Verificar si ya existe una valoración para este pedido
        $query_check = "SELECT COUNT(*) AS count FROM valoracion WHERE id_pedido = ? AND id_usuario = ?";
        $stmt_check = $conexion->prepare($query_check);
        $stmt_check->bind_param("ii", $id_pedido, $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();
        $stmt_check->close();

        if ($row_check['count'] > 0) {
            $_SESSION['mensaje_error'] = 'Ya has valorado este pedido.';
            header("Location: ../../index/index-perfil.php");
            exit();
        }

        // Insertar la nueva valoración
        $query_insert = "INSERT INTO valoracion (id_pedido, id_usuario, cantidad_estrellas, comentario, fecha_valoracion)
                         VALUES (?, ?, ?, ?, NOW())";
        $stmt_insert = $conexion->prepare($query_insert);

        if (!$stmt_insert) {
            throw new Exception("Error al preparar la consulta: " . $conexion->error);
        }

        $stmt_insert->bind_param("iiis", $id_pedido, $user_id, $cantidad_estrellas, $comentario);

        if (!$stmt_insert->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt_insert->error);
        }

        $_SESSION['mensaje_exito'] = '¡Tu valoración se ha registrado exitosamente!';
        $stmt_insert->close();
    } catch (Exception $e) {
        $_SESSION['mensaje_error'] = "Error: " . $e->getMessage();
        error_log("Error al guardar valoración: " . $e->getMessage());
    } finally {
        $conexion->close();
        header("Location: ../../index/index-perfil.php");
        exit();
    }
} else {
    $_SESSION['mensaje_error'] = 'Método no permitido.';
    header("Location: ../../index/index-perfil.php");
    exit();
}
