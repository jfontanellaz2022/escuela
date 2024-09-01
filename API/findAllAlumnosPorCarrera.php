<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib');
require_once 'Alumno.php';
require_once 'SanitizeCustom.class.php';
require_once 'seguridadNivel2.php';

$id_carrera = (isset($_POST['carrera']))?SanitizeVars::INT($_POST['carrera']):false;

if ($id_carrera) {
   $objeto = new Alumno;
   $arr_alumnos = $objeto->getAllAlumnosByCarrera($id_carrera);
   if (is_array($arr_alumnos)) {
      $array_resultados['codigo'] = 200;
      $array_resultados['mensaje'] = "ok";
      $array_resultados['datos'] = $arr_alumnos;
   } else {
      $array_resultados['codigo'] = 500;
      $array_resultados['mensaje'] = "Error 500: Hubo un error en la consulta.";
      $array_resultados['datos'] = [];
   }
} else {
   $array_resultados['codigo'] = 400;
   $array_resultados['mensaje'] = "Error 400: No ingreso la Carrera.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);







?>
