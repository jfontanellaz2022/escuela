<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'./');

require_once "Carrera.php";
require_once "Sanitize.class.php";
require_once "_seguridad.php";

$idCarrera = (isset($_POST['carrera']) && $_POST['carrera']!=NULL)?SanitizeVars::INT($_POST['carrera']):false;
$arr_materias = $array_resultados = array();
if ($idCarrera) {
    $obj = new Carrera();
    $arr_materias = $obj->getMateriasPorIdCarrera($idCarrera);
    //var_dump($arr_materias);exit;

    if (!empty($arr_materias)) {
      $array_resultados['codigo'] = 200;
      $array_resultados['mensaje'] = "ok";
      $array_resultados['datos'] = $arr_materias;
    } else {
      $array_resultados['codigo'] = 202;
      $array_resultados['mensaje'] = "No existen resultados.";
      $array_resultados['datos'] = $arr_materias;
    }
} else {
  $array_resultados['codigo'] = 400;
  $array_resultados['mensaje'] = "Falta un datos obligatorio.";
  $array_resultados['datos'] = $arr_materias;
}

echo json_encode($array_resultados);
?>
