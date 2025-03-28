<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'./');

require_once "Carrera.php";
require_once "Sanitize.class.php";
require_once "_seguridad.php";

$id = ( isset($_POST['id']) )?SanitizeVars::INT($_POST['id']):false;
$arr_resultados = $arr_datos = [];

if ($id) {
    $objeto = new Carrera();
    $arr_datos = $objeto->getCarreraById($id);

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
