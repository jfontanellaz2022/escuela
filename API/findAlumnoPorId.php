<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'Alumno.php';
require_once 'SanitizeCustom.class.php';
require_once 'seguridadNivel2.php';

$id_alumno = (isset($_POST['alumno']))?SanitizeVars::INT($_POST['alumno']):false;
$token = (isset($_GET['token']))?$_GET['token']:false;

if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 500;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}

if ($id_carrera) {
   $objeto = new Alumno;
   $arr_alumno = $objeto->getById($id_alumno);
   if (is_array($arr_alumno)) {
      $array_resultados['codigo'] = 200;
      $array_resultados['class'] = 'success';
      $array_resultados['mensaje'] = "ok";
      $array_resultados['datos'] = $arr_alumno;
   } else {
      $array_resultados['codigo'] = 500;
      $array_resultados['class'] = 'danger';
      $array_resultados['mensaje'] = "Error 500: Hubo un error en la consulta.";
      $array_resultados['datos'] = [];
   }
} else {
   $array_resultados['codigo'] = 500;
   $array_resultados['class'] = 'danger';
   $array_resultados['mensaje'] = "Error 400: No ingreso la Carrera.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);







?>
