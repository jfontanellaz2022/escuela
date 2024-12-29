<?php
session_start();
if (!in_array('Alumno',$_SESSION['arreglo_credenciales_usuario'])) {
    session_destroy();
    header('location: ../index.php');
} 

?>
