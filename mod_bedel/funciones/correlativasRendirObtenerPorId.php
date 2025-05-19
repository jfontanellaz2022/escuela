<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once "CorrelativasParaRendir.php";
require_once "Sanitize.class.php";


$id = ( isset($_POST['id']) )?SanitizeVars::INT($_POST['id']):false;
$arr_resultados = $arr_datos = [];

if ($id) {
    //carrera materia id carrera materia_requerida id condicion id
    //die($sql);
    $cpc = new CorrelativasParaRendir();
    $arr_datos = $cpc->getCorrelativasRendirById($id);

    if (!$arr_datos) {
      $arr_resultados['codigo'] = 400;
      $arr_resultados['datos'] = "No existe el Registro.";
    } else {
      $arr_resultados['codigo'] = 200;
      $arr_resultados['datos'] = $arr_datos;
    }
} else {
  $arr_resultados['codigo'] = 400;
  $arr_resultados['datos'] = "El ID del Registro es Incorrecto.";
}

//var_dump($arr_resultados);die;
echo json_encode($arr_resultados);

?>
