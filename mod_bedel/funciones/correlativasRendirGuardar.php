<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once "Sanitize.class.php";
require_once "CorrelativasParaRendir.php";

//$accion = (isset($_POST['accion']) && $_POST['accion']!=NULL)?SanitizeVars::STRING($_POST['accion']):false;
$id = (isset($_POST['id']) && $_POST['id']!=NULL)?SanitizeVars::INT($_POST['id']):false;
$materia_id = (isset($_POST['materia_id']) && $_POST['materia_id']!=NULL)?SanitizeVars::INT($_POST['materia_id']):false;
$materia_requerida_id = (isset($_POST['materia_requerida_id']) && $_POST['materia_requerida_id']!=NULL)?SanitizeVars::INT($_POST['materia_requerida_id']):false;
$condicion_id = (isset($_POST['condicion_id']) && $_POST['condicion_id']!=NULL)?SanitizeVars::INT($_POST['condicion_id']):false;

$entidad = "Correlativas para cursar";
$arr_resultados = $arr_argumentos = [];


if ($materia_id && $materia_requerida_id && $condicion_id) {
      
      if ($id) $arr_argumentos['id'] = $id;
      if ($materia_id) $arr_argumentos['materia_id'] = $materia_id;
      if ($materia_requerida_id) $arr_argumentos['materia_requerida_id'] = $materia_requerida_id;
      if ($condicion_id) $arr_argumentos['condicion_id'] = $condicion_id;

      //var_dump($arr_argumentos);die;

      $correlativas = new CorrelativasParaRendir();
      $res = $correlativas->save($arr_argumentos); 
                 
      if ($res) {
         $arr_resultados['codigo'] = 200;
         $arr_resultados['mensaje'] = "Los datos de la $entidad fueron Actualizados Exitosamente.";
      } else {
         $arr_resultados['codigo'] = 500;
         $arr_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos de la $entidad. ";
      }

};

echo json_encode($arr_resultados);


?>
