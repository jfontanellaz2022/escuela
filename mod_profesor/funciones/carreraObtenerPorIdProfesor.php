<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'./');

require_once "ProfesorPerteneceCarreraDetalle.php";
require_once "Sanitize.class.php";
require_once "_seguridad.php";

$profesor_id = ( isset($_POST['profesor_id']) )?SanitizeVars::INT($_POST['profesor_id']):false;
$arr_resultados = $arr_datos = [];

if ($profesor_id) {
    $profesor_carrera = new ProfesorPerteneceCarreraDetalle();
    $arr_datos = $profesor_carrera->getProfesorPerteneceCarreraByIdProfesorDetalle($profesor_id);

    if (empty($arr_datos)) {
      $arr_resultados['codigo'] = 11;
      $arr_resultados['datos'] = "No existe el Registro.";
    } else {
      $arr_resultados['codigo'] = 100;
      $arr_resultados['datos'] = $arr_datos;
    }
} else {
  $arr_resultados['codigo'] = 10;
  $arr_resultados['datos'] = "El ID del Registro es Incorrecto.";
}

echo json_encode($arr_resultados);

?>
