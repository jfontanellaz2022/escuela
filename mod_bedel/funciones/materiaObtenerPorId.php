<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once 'verificarCredenciales.php';
require_once 'SanitizeCustom.class.php';
require_once 'Materia.php';

$array_resultados = array();
$materia_id = isset($_POST['id'])?SanitizeCustom::INT($_POST['id']):false;

if($materia_id) {
        $obj = new Materia();
        $arr_res = $obj->getMateriaById($materia_id);
        
        if (count($arr_res)>0) {
                $array_resultados['codigo'] = 200;
                $array_resultados['alert'] = 'success';
                $array_resultados['datos'] = $arr_res;
          } else {
                $array_resultados['codigo'] = 500;
                $array_resultados['alert'] = 'danger';
                $array_resultados['mensaje'] = "No existe la Materia.";
          }
       
} else {
      $array_resultados['codigo'] = 500;
      $array_resultados['alert'] = 'danger';
      $array_resultados['mensaje'] = "No tiene ID de Materia.";
}
echo json_encode($array_resultados);

?>
