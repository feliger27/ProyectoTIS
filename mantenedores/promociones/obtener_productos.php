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
    }

    if ($tabla) {
        $sql = "SELECT $id_campo AS id, $nombre_campo AS nombre FROM $tabla";
        $result = $conexion->query($sql);
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($productos);
?>
