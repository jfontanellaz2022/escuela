<?php
session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');

if (!in_array('Profesor',$_SESSION['arreglo_credenciales_usuario'])) {
    session_destroy();
    header('location: ../index.php');
} 


?>
