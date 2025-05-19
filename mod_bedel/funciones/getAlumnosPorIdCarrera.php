<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'AlumnoEstudiaCarrera.php';

$idCarrera = SanitizeVars::INT($_POST['carrera_id']);

$array_resultados = array();

if ($idCarrera) {
    $obj = new AlumnoEstudiaCarrera();
    $filas = $obj->getAlumnoEstudiaCarreraById($idCarrera);
    
    if (count($filas)>0) {
        $array_resultados['codigo'] = 200;
        $array_resultados['alert'] = 'success';
        $array_resultados['mensaje'] = 'OK';
        $array_resultados['datos'] = $filas;
    } else {
        $array_resultados['codigo'] = 500;
        $array_resultados['alert'] = 'danger';
        $array_resultados['mensaje'] = 'No existen resultados para esa Carrera.';
    }

} else {
    $array_resultados['codigo'] = 500;
    $array_resultados['alert'] = 'danger';
    $array_resultados['mensaje'] = 'Faltan Datos Obligatarios.';
}
echo json_encode($array_resultados);

?>
