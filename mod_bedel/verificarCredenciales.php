<?php
session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');

if ( !isset($_SESSION['arreglo_credenciales_usuario']) ) {
    session_destroy();
    header('location: ../index.php');
};

if ( !in_array('Bedel',$_SESSION['arreglo_credenciales_usuario']) ) {
    session_destroy();
    header('location: ../index.php');
};
?>
