<?php
include '../../conexion.php';
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado.']);
    exit();
}

$user_id = $_SESSION['user_id']; // ID del usuario actual

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar y sanitizar los datos del formulario
    $calle = mysqli_real_escape_string($conexion, $_POST['calle'] ?? '');
    $numero = mysqli_real_escape_string($conexion, $_POST['numero'] ?? '');
    $ciudad = mysqli_real_escape_string($conexion, $_POST['ciudad'] ?? '');

    // Validar que todos los campos requeridos estén completos
    if (empty($calle) || empty($numero) || empty($ciudad)) {
        echo json_encode(['success' => false, 'error' => 'Por favor, complete todos los campos.']);
        exit();
    }

    // Intentar insertar la dirección
    $query_direccion = "INSERT INTO direccion (calle, numero, ciudad) VALUES (?, ?, ?)";
    $stmt_direccion = $conexion->prepare($query_direccion);
    if (!$stmt_direccion) {
        echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta: ' . $conexion->error]);
        exit();
    }

    $stmt_direccion->bind_param("sis", $calle, $numero, $ciudad);
    if ($stmt_direccion->execute()) {
        $direccion_id = $stmt_direccion->insert_id; // Obtener el ID de la nueva dirección

        // Asociar la dirección al usuario actual
        $query_direccion_usuario = "INSERT INTO direccion_usuario (id_usuario, id_direccion) VALUES (?, ?)";
        $stmt_direccion_usuario = $conexion->prepare($query_direccion_usuario);
        if (!$stmt_direccion_usuario) {
            echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta de relación usuario-dirección: ' . $conexion->error]);
            exit();
        }

        $stmt_direccion_usuario->bind_param("ii", $user_id, $direccion_id);
        if ($stmt_direccion_usuario->execute()) {
            // Respuesta exitosa
            echo json_encode([
                'success' => true,
                'id_direccion' => $direccion_id,
                'mensaje' => 'Dirección guardada y asociada correctamente.'
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al asociar la dirección con el usuario: ' . $stmt_direccion_usuario->error]);
        }
        $stmt_direccion_usuario->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al guardar la dirección: ' . $stmt_direccion->error]);
    }
    $stmt_direccion->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}

$conexion->close();
?>
