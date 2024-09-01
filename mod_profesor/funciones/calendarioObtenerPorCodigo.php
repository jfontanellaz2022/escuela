<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'./');

require_once "CalendarioAcademico.php";
require_once "Sanitize.class.php";
require_once "_seguridad.php";

$codigo = ( isset($_POST['codigo']) )?SanitizeVars::INT($_POST['codigo']):false;
$arr_resultados = $arr_datos = [];

if ($codigo) {
    $objeto = new CalendarioAcademico();
    $arr_datos = $objeto->getEventoActivoByCodigo($codigo);
    $arr_resultados['codigo'] = 100;
    $arr_resultados['datos'] = $arr_datos;
} else {
  $arr_resultados['codigo'] = 10;
  $arr_resultados['datos'] = "El Codigo del Registro es Incorrecto.";
}

echo json_encode($arr_resultados);

?>
