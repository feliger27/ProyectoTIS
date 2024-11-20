<?php
include '../../conexion.php';

$tipo = $_GET['tipo'];
$productos = [];

if ($tipo) {
    $tabla = '';
    $id_campo = '';
    $nombre_campo = '';

    switch ($tipo) {
        case 'hamburguesa':
            $tabla = 'hamburguesa';
            $id_campo = 'id_hamburguesa';
            $nombre_campo = 'nombre_hamburguesa';
            break;
        case 'postre':
            $tabla = 'postre';
            $id_campo = 'id_postre';
            $nombre_campo = 'nombre_postre';
            break;
        case 'bebida':
            $tabla = 'bebida';
            $id_campo = 'id_bebida';
            $nombre_campo = 'nombre_bebida';
            break;
        case 'acompaniamiento':
            $tabla = 'acompaniamiento';
            $id_campo = 'id_acompaniamiento';
            $nombre_campo = 'nombre_acompaniamiento';
            break;
        case 'combo': // Nueva opción para combos
            $tabla = 'combo';
            $id_campo = 'id_combo';
            $nombre_campo = 'nombre_combo';
            break;
    }

    if ($tabla) {
        // Consulta para obtener productos dependiendo del tipo seleccionado
        $sql = "SELECT $id_campo AS id, $nombre_campo AS nombre FROM $tabla";
        $result = $conexion->query($sql);

        // Guardar los productos en el array
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
}

// Devolver los productos como un JSON
header('Content-Type: application/json');
echo json_encode($productos);
?>

