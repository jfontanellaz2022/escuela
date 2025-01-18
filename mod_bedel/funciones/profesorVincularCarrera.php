<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once "Sanitize.class.php";
require_once "ProfesorPerteneceCarrera.php";

$idProfesor = (isset($_POST['profesor_id']))?SanitizeVars::INT($_POST['profesor_id']):false;
$idCarrera = (isset($_POST['carrera_id']))?SanitizeVars::INT($_POST['carrera_id']):false;

$array_resultados = array();

if ($idProfesor && $idCarrera) {

      $objPPC = new ProfesorPerteneceCarrera();

      $param['profesor_id'] = $idProfesor;
      $param['carrera_id'] = $idCarrera;

      $res = $objPPC->save($param);
      if ($res) {
            $array_resultados['codigo'] = 200;
            $array_resultados['alert'] = 'success';
            $array_resultados['mensaje'] = "El Profesor con ID <strong>$idProfesor</strong> se vinculó a esa carrera.";   
      } else {
            $array_resultados['codigo'] = 500;
            $array_resultados['alert'] = 'danger';
            $array_resultados['mensaje'] = "El Profesor con ID <strong>$idProfesor</strong> ya se encontraba vinculado a esa carrera.";  
      }
} else {
      $array_resultados['codigo'] = 500;
      $array_resultados['alert'] = 'danger';
      $array_resultados['mensaje'] = "Ocurrio un Error.";  
}

echo json_encode($array_resultados);



?>
