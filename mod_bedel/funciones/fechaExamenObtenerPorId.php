<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'MateriaFechaExamen.php';


$fecha_examen_id = (isset($_POST['fecha_examen_id']) && $_POST['fecha_examen_id']!=NULL)?SanitizeVars::INT($_POST['fecha_examen_id']):false;
$array_resultados = array();

if ($fecha_examen_id) {
    $obj = new MateriaFechaExamen();
    $arr_res = $obj->getMateriaFechaExamenById($fecha_examen_id);
    $array_resultados['codigo'] = 200;
    $array_resultados['alert'] = 'success';
    $array_resultados['mensaje'] = 'OK';
    $array_resultados['datos'] = $arr_res;
   
} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['data'] = "Faltan Datos Obligatorios.";
}
echo json_encode($array_resultados);

?>
