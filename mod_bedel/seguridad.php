<?php
session_start();
require_once "config.php";

$redirect = $url.'logout.php';

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
   session_destroy();
   header("location: $redirect");
}

?>
