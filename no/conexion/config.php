<?php
/**
 *  Archivo: config.php
 *	Utilidad: Este archivo define constantes necesarias para la conexion con la base de datos.
*/

///////////////////// BASE DE DATOS local ///////////////////////////////////////////
define('DB_TYPE', "mysql");
define('DB_HOST', "localhost");
define('DB_PORT', "3306");
define('DB_NAME', "uiakkdaq_escuela");
define('DB_USER', "root");
define('DB_PASS', "");

$url = 'http://'.$_SERVER["HTTP_HOST"].'/ens40_2912/';
$MY_SECRET = 'MI_SECRETO_ESCONDIDO';
$secreto = '40_escuela_40';

///////////////////////////////////////////////////////////////////////////////
?>
