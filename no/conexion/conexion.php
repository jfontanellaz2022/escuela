<?php
/**
 *  Archivo: config.php
 *	Utilidad: Este archivo define constantes necesarias para la conexion con la base de datos.
*/

///////////////////// BASE DE DATOS local ///////////////////////////////////////////
require_once('config.php');

//var_dump("**************:",$DB->getHost(),$DB->getUser(), $DB->getPassword(), $DB->getDataBase()); exit;
$conex = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

if (!$conex) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuracin: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuracin: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

mysqli_set_charset($conex, 'utf8');


///////////////////////////////////////////////////////////////////////////////
?>