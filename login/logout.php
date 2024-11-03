<?php
session_start();
if(session_destroy()) {
    header("Location: ../index/index-lobby.php"); 
    exit();
}
?>
