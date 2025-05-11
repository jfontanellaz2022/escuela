<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
//require_once "seguridadNivel1.php";
require_once 'AlumnoEstudiaCarrera.php';
require_once 'SanitizeCustom.class.php';

$id_alumno = (isset($_POST['alumno']))?SanitizeVars::INT($_POST['alumno']):false;
$id_carrera = (isset($_POST['carrera']))?SanitizeVars::INT($_POST['carrera']):false;
$token = (isset($_GET['token']))?$_GET['token']:false;

//var_dump($id_alumno,$id_carrera);exit;

/*
if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 500;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}*/

if ($id_alumno && $id_carrera) {
   $objeto = new AlumnoEstudiaCarrera;
   $arr_res = $objeto->hasMesaEspecial(["alumno_id"=>$id_alumno,"carrera_id"=>$id_carrera]);
   if ($arr_res=="Si") {
      $array_resultados['codigo'] = 200;
      $array_resultados['mensaje'] = "ok";
      $array_resultados['datos'] = true;
   } else {
      $array_resultados['codigo'] = 200;
      $array_resultados['mensaje'] = "ok";
      $array_resultados['datos'] = false;
   }
} else {
   $array_resultados['codigo'] = 400;
   $array_resultados['mensaje'] = "Error 400: No ingreso la Carrera o el Alumno.";
   $array_resultados['datos'] = false;
}

echo json_encode($array_resultados);







?>
