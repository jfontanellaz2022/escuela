<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once "CorrelativasParaCursar.php";
require_once "Sanitize.class.php";

$accion = (isset($_POST['accion']) && $_POST['accion']!=NULL)?SanitizeVars::STRING($_POST['accion']):false;
$entidades_a_eliminar = (isset($_POST['id']) && $_POST['id']!=NULL)?$_POST['id']:false;

$arr_resultados = [];
$entidad = "Correlativas para cursar";

if ($entidades_a_eliminar) {
   $arreglo_entidades = explode(',',$entidades_a_eliminar);
   $cantidad_entidades = count($arreglo_entidades);
   $correlativa = new CorrelativasParaCursar();
   $band = TRUE;
   foreach($arreglo_entidades as $idEntidad) { 
      $res = $correlativa->deleteCorrelativasCursadoById($idEntidad);
      if (!$res) {
         $band = FALSE;
         break;
      }         
   }

   if ($band) {
      $arr_resultados['codigo'] = 200;
      $arr_resultados['mensaje'] = "El registro de datos de las $entidad fue Eliminado Exitosamente.";
   } else {
      $arr_resultados['codigo'] = 500;
      $arr_resultados['mensaje'] = "Hubo un Error en la Eliminación de los datos de la $entidad. ";
   };
         
} else {
   $arr_resultados['codigo'] = 500;
   $arr_resultados['mensaje'] = "Hubo un Error en la eliminación de la $entidad. ";
} 

echo json_encode($arr_resultados);


?>
