<?php
session_start();
if (($_SESSION['tipoUsuario']!='1')||(!$_SESSION['tipoUsuario'])) {
    session_destroy();
    header('location: https://escuela40.net');
}



?>
