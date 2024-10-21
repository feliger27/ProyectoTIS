<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_bebida = $_POST['id_bebida'];

    $sql = "DELETE FROM bebida WHERE id_bebida = $id_bebida";

    if ($conexion->query($sql) === TRUE) {
        header("Location: listar.php?eliminado=true&id=$id_bebida");
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>
