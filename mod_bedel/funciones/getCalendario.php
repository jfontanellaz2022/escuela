<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'CalendarioAcademico.php';

$array_resultados = array();

    $obj = new CalendarioAcademico();
    $filas = $obj->getCalendarioEventosDetalle();
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
  
echo json_encode($array_resultados);

?>
