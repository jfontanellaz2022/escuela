<?php
session_start();
session_destroy();
require_once "./conexion/config.php";
header('location: ' . $url . 'index.php');
 ?>
