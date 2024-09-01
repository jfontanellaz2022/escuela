<?php
session_start();
set_include_path('../../conexion/');

require_once "config.php";

$redirect = $url.'logout.php';

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
   session_destroy();
   header("location: $redirect");
}

?>
