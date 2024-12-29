<?php
session_start();
if (($_SESSION['tipoUsuario']!='2')||(!$_SESSION['tipoUsuario'])) {
    session_destroy();
    header('location: http://www.escuela40.net');
};
?>
