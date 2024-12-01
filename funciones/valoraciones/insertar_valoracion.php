<?php
include '../../conexion.php';
session_start();

// Validar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'];
    $cantidad_estrellas = $_POST['cantidad_estrellas'];
    $comentario = $_POST['comentario'];
    $fecha_valoracion = date('Y-m-d'); // Fecha actual
    $id_usuario = $_SESSION['user_id']; // ID del usuario desde la sesión

    // Validar campos principales
    if (!empty($id_pedido) && !empty($cantidad_estrellas) && !empty($comentario) && !empty($id_usuario)) {
        // Verificar si hay una hamburguesa asociada al pedido
        $query_hamburguesa = "SELECT ph.id_hamburguesa 
                              FROM pedido_hamburguesa ph
                              WHERE ph.id_pedido = ?";
        $stmt_hamburguesa = $conexion->prepare($query_hamburguesa);
        $stmt_hamburguesa->bind_param("i", $id_pedido);
        $stmt_hamburguesa->execute();
        $result_hamburguesa = $stmt_hamburguesa->get_result();
        $id_hamburguesa = null;

        if ($result_hamburguesa->num_rows > 0) {
            $hamburguesa = $result_hamburguesa->fetch_assoc();
            $id_hamburguesa = $hamburguesa['id_hamburguesa'];
        }
        $stmt_hamburguesa->close();

        // Insertar la valoración
        $query_valoracion = "INSERT INTO valoracion (id_usuario, id_pedido, id_hamburguesa, cantidad_estrellas, comentario, fecha_valoracion) 
                             VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_valoracion = $conexion->prepare($query_valoracion);
        $stmt_valoracion->bind_param("iiiiss", $id_usuario, $id_pedido, $id_hamburguesa, $cantidad_estrellas, $comentario, $fecha_valoracion);

        if ($stmt_valoracion->execute()) {
            $_SESSION['mensaje_exito'] = "¡Reseña añadida exitosamente!";
            header("Location: ../../index/index-perfil.php");
            exit();
        } else {
            echo "Error al insertar la reseña: " . $conexion->error;
        }
        $stmt_valoracion->close();
    } else {
        echo "Por favor, complete todos los campos.";
    }
} else {
    echo "Método no permitido.";
}
?>
