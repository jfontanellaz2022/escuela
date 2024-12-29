<?php
session_start();
require_once('../conexion/config.php');

if ((!in_array($_SESSION['tipoUsuario'],[1,2,3]))||(!$_SESSION['tipoUsuario'])) {
    session_destroy();
    header('location: ' . $url);
}

?>
