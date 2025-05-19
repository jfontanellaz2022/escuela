<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once "CalendarioAcademico.php";
require_once "Sanitize.class.php";

$array_resultados = array();

$obj = new CalendarioAcademico();
$calendario_id = $obj->getLastInscripcionExamen();

if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existen Inscripciones.";
}

echo json_encode($array_resultados);



?>
