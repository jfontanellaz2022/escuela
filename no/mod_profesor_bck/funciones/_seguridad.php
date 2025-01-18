<?php
session_start();
set_include_path('../../config/');

require_once "parameters.php";

$redirect = $url.'logout.php';

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
   session_destroy();
   header("location: $redirect");
}

?>
