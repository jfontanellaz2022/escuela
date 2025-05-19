<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'CalendarioAcademico.php';

ini_set("default_charset", "UTF-8");

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$entidades_a_eliminar = ( isset($_POST['id']) && $_POST['id']!="" )?$_POST['id']:false;
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$array_resultados = array();

if ($entidades_a_eliminar) {
      
      $arreglo_entidades = explode(',',$entidades_a_eliminar);
      $cantidad_entidades = count($arreglo_entidades);

      $objCalendario = new CalendarioAcademico();
      foreach($arreglo_entidades as $idEntidad) {
            $objCalendario->deleteCalendarioAcademicoById($idEntidad);
      } // END FOR

      $array_resultados['codigo'] = 200;
      $array_resultados['alert'] = 'success';
      $array_resultados['mensaje'] = 'Eliminación Se ha realizado correctamente.'; 
      
} else {
      $array_resultados['codigo'] = 500;
      $array_resultados['alert'] = 'danger';
      $array_resultados['mensaje'] = 'Eliminación No Se ha realizado correctamente.'; 
}

echo json_encode($array_resultados);



?>
