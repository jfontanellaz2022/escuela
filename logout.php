<?php
set_include_path('./app/models/');
session_start();
if (!isset($_SESSION['token'])) {
    header("location: index.php");
}
session_destroy();
require_once "Parameters.php";

header('location: ' . Parameters::VALOR_URL . '/index.php');
 ?>
