<?php
session_start();

if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
} else {
  session_destroy();
  header('location: index.php');
}

//echo "EMAIL@: ".$_SESSION['email'];
?>
