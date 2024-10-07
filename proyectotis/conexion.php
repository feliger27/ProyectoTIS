<?php
    $conexion = mysqli_connect("localhost","root","","hamburgeeks");
    //servidor,user,mysql,pass mysql, bdname
    if($conexion->connect_error) {
        die("Conexión Fallida: ". $conn->connect_error);
    }
?>
