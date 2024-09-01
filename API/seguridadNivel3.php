<?php
session_start();
if (($_SESSION['tipoUsuario']!='3')||(!$_SESSION['tipoUsuario'])) {
    session_destroy();
    header('location: http://www.escuela40.net');
}



?>
