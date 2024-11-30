<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Debes iniciar sesión para agregar una dirección.'
    ]);
    exit;
}

include '../../conexion.php';

// Validar datos enviados por el formulario
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['calle'], $data['numero'], $data['ciudad'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Faltan datos necesarios para agregar la dirección.'
    ]);
    exit;
}

$calle = $conexion->real_escape_string($data['calle']);
$numero = (int)$data['numero'];
$ciudad = $conexion->real_escape_string($data['ciudad']);
$depto = isset($data['depto_oficina_piso']) ? $conexion->real_escape_string($data['depto_oficina_piso']) : null;
$guardarFuturo = isset($data['guardar_futuro']) ? (bool)$data['guardar_futuro'] : false;

try {
    $conexion->begin_transaction();

    // Insertar dirección en la tabla `direccion`
    $queryDireccion = "INSERT INTO direccion (calle, numero, ciudad, depto_oficina_piso) 
                        VALUES ('$calle', $numero, '$ciudad', " . ($depto ? "'$depto'" : "NULL") . ")";
    if (!$conexion->query($queryDireccion)) {
        throw new Exception('Error al guardar la dirección: ' . $conexion->error);
    }

    $idDireccion = $conexion->insert_id;

    // Si se seleccionó "Guardar dirección para futuros pedidos", relacionar con el usuario
    if ($guardarFuturo) {
        $idUsuario = (int)$_SESSION['id_usuario'];
        $queryRelacion = "INSERT INTO direccion_usuario (id_usuario, id_direccion) 
                          VALUES ($idUsuario, $idDireccion)";
        if (!$conexion->query($queryRelacion)) {
            throw new Exception('Error al asociar la dirección al usuario: ' . $conexion->error);
        }
    }

    $conexion->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Dirección agregada correctamente.',
        'id_direccion' => $idDireccion
    ]);
} catch (Exception $e) {
    $conexion->rollback();
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>