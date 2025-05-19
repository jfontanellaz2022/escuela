<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'CarreraTieneMateria.php';

$idCarrera = SanitizeVars::INT($_POST['carrera_id']);
$array_resultados = array();

if ($idCarrera) {
    $obj = new CarreraTieneMateria();
    $filas = $obj->getMateriasByIdCarreraDetalle($idCarrera);
    if (count($filas)>2) {
        $array_resultados['codigo'] = 200;
        $array_resultados['alert'] = 'success';
        $array_resultados['mensaje'] = 'OK';
        $array_resultados['datos'] = $filas;
    } else {
        $array_resultados['codigo'] = 500;
        $array_resultados['mensaje'] = "No existe Materias para la Carrera.";
        $array_resultados['alert'] = 'danger';
    }
  
} else {
   $array_resultados['codigo'] = 500;
   $array_resultados['mensaje'] = "Faltan datos obligatorios.";
   $array_resultados['alert'] = 'danger';
}

echo json_encode($array_resultados);

?>
