<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS MATERIAS        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib');
require_once 'seguridadNivel2.php';
require_once 'SanitizeCustom.class.php';
require_once 'Materia.php';

$array_resultados = [];

$m = new Materia();
$arr_materias = $m->getMaterias();

$array_resultados['codigo'] = 200;
$array_resultados['mensaje'] = "ok";
$array_resultados['datos'] = $arr_materias;

echo json_encode($array_resultados);

?>
