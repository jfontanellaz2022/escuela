<?php

//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODOS LOS ALUMNOS DE UN ALUMNO POR ID ALUMNO                                 **
//***************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
require_once 'Tipificacion.php';
require_once 'SanitizeCustom.class.php';

$token = (isset($_GET['token']))?$_GET['token']:false;

if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 500;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}

$objeto = new Tipificacion();
$arr_formas_cursado = $objeto->getAllAlumnoTipoCursado();

if (is_array($arr_formas_cursado)) {
   $array_resultados['codigo'] = 200;
   $array_resultados['mensaje'] = "ok";
   $array_resultados['datos'] = $arr_formas_cursado;
} else {
   $array_resultados['codigo'] = 500;
   $array_resultados['mensaje'] = "Error 500: Hubo un error en la consulta.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);


?>