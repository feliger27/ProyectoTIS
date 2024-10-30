<?php
    $conexion = mysqli_connect("localhost","root","","hamburgeeks");
    if($conexion->connect_error) {
        die("Conexión Fallida: ". $conn->connect_error);
    }
?>
