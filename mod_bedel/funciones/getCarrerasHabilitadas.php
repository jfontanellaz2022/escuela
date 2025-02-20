<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once "Carrera.php";

$array_resultados = array();
$objCarrera = new Carrera();
$arr_carreras = $objCarrera->getCarrerasHabilitadas();
      
if (count($arr_carreras)>0) {
      $array_resultados['codigo'] = 200;
      $array_resultados['alert'] = 'success';
      $array_resultados['datos'] = $arr_carreras;
} else {
      $array_resultados['codigo'] = 500;
      $array_resultados['alert'] = 'danger';
      $array_resultados['mensaje'] = "No existe la Carrera.";
}
echo json_encode($array_resultados);

?>
