<?php

$conn = mysqli_connect("localhost", "root", "","hamburgeeks");
if(mysqli_connect_error()){
    echo "Failed to connect to MYSQL". mysqli_connect_error();
}