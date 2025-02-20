<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once "Alumno.php";


$idCarrera = SanitizeVars::INT($_POST['carrera_id']);
$anio = SanitizeVars::INT($_POST['anio']);

$array_resultados = array();
if ($idCarrera && $anio) {
    $param['carrera_id'] = $idCarrera;
    $param['anio'] = $anio;
    $obj = new Alumno();
    $arr_datos = $obj->getAllAlumnosByCarrera($param);

    
    $array_resultados['codigo'] = 200;
    $array_resultados['alert'] = "success";
    $array_resultados['mensaje'] = "OK";
    $array_resultados['datos'] = $arr_datos;



} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['datos'] = "Faltan Datos Obligatarios.";
}


echo json_encode($array_resultados);


?>
