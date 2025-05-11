<?php
session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');
//var_dump($_SESSION['arreglo_credenciales_usuario']);
if ( !isset($_SESSION['arreglo_credenciales_usuario']) ) {
    session_destroy();
    header('location: ../index.php');
} else {
    if ( !in_array('Alumno',$_SESSION['arreglo_credenciales_usuario']) ) {
        session_destroy();
        header('location: ../index.php');
    };
}


?>
