<?php

//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODOS LOS ALUMNOS DE UN ALUMNO POR ID ALUMNO                                 **
//***************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib');
require_once 'SanitizeCustom.class.php';
//include_once 'seguridadNivel2.php';

require_once 'Tipificacion.php';

$array_resultados = [];

$objeto = new Tipificacion();
$arr_tipos_estado = $objeto->getAllAlumnoEstadosMateria();

if (!empty($arr_tipos_estado)) {
   $array_resultados['codigo'] = 200;
   $array_resultados['alert'] = "success";
   $array_resultados['mensaje'] = "ok";
   $array_resultados['datos'] = $arr_tipos_estado;
} else {
   $array_resultados['codigo'] = 500;
   $array_resultados['alert'] = "danger";
   $array_resultados['mensaje'] = "Hubo un error en la consulta.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);


?>