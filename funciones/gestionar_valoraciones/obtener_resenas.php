<?php
include '../../conexion.php';

header('Content-Type: application/json');

// Obtener los parámetros desde la URL
$nombre_producto = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$tipo_producto = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if (empty($nombre_producto) || empty($tipo_producto)) {
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
    exit;
}

// Determinar la tabla y columna según el tipo de producto
switch ($tipo_producto) {
    case 'hamburguesa':
        $tabla_producto = 'hamburguesa';
        $columna_nombre = 'nombre_hamburguesa';
        $columna_id = 'id_hamburguesa';
        break;
    case 'postre':
        $tabla_producto = 'postre';
        $columna_nombre = 'nombre_postre';
        $columna_id = 'id_postre';
        break;
    case 'bebida':
        $tabla_producto = 'bebida';
        $columna_nombre = 'nombre_bebida';
        $columna_id = 'id_bebida';
        break;
    case 'acompaniamiento':
        $tabla_producto = 'acompaniamiento';
        $columna_nombre = 'nombre_acompaniamiento';
        $columna_id = 'id_acompaniamiento';
        break;
    case 'combo':
        $tabla_producto = 'combo';
        $columna_nombre = 'nombre_combo';
        $columna_id = 'id_combo';
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Tipo de producto no válido']);
        exit;
}

// Consultar el ID del producto
$query_producto = "SELECT $columna_id FROM $tabla_producto WHERE $columna_nombre = ?";
$stmt_producto = $conexion->prepare($query_producto);
$stmt_producto->bind_param("s", $nombre_producto);
$stmt_producto->execute();
$result_producto = $stmt_producto->get_result();
$producto = $result_producto->fetch_assoc();

if (!$producto) {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    exit;
}

$id_producto = $producto[$columna_id];

// Consultar las reseñas relacionadas al producto
$query_resenas = "SELECT 
                    v.cantidad_estrellas AS puntuacion, 
                    v.comentario, 
                    u.nombre AS usuario, 
                    v.fecha_valoracion 
                  FROM valoracion v
                  JOIN usuario u ON v.id_usuario = u.id_usuario
                  WHERE v.$columna_id = ?";
$stmt_resenas = $conexion->prepare($query_resenas);
$stmt_resenas->bind_param("i", $id_producto);
$stmt_resenas->execute();
$result_resenas = $stmt_resenas->get_result();

$reseñas = [];
while ($row = $result_resenas->fetch_assoc()) {
    $reseñas[] = $row;
}

if (empty($reseñas)) {
    echo json_encode(['success' => false, 'message' => 'No hay reseñas para este producto']);
} else {
    echo json_encode(['success' => true, 'reseñas' => $reseñas]);
}
