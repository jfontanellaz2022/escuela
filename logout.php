<?php
set_include_path('./app/models/');
session_start();
session_destroy();
require_once "Parameters.php";

header('location: ' . Parameters::VALOR_URL . '/index.php');
 ?>
