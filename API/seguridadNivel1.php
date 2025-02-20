<?php
// PERMITE A ALUMNOS BEDELES Y PROFESORES
session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');
//var_dump($_SESSION['arreglo_credenciales_usuario']);exit;
if ( !isset($_SESSION['arreglo_credenciales_usuario']) ) {
    session_destroy();
    header('location: ../index.php');
};

if ( !in_array($_SESSION['arreglo_credenciales_usuario'][0],['Alumno','Profesor','Bedel']) ) {
    session_destroy();
    header('location: ../index.php');
};

?>

