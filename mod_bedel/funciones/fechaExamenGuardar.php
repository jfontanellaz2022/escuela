<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once "Sanitize.class.php";
require_once "MateriaFechaExamen.php";

$calendario_id = (isset($_POST['calendario_id']) && $_POST['calendario_id']!=NULL)?SanitizeVars::INT($_POST['calendario_id']):false;
$materia_id = (isset($_POST['materia_id']) && $_POST['materia_id']!=NULL)?SanitizeVars::INT($_POST['materia_id']):false;
$llamado = (isset($_POST['llamado']) && $_POST['llamado']!=NULL)?SanitizeVars::INT($_POST['llamado']):false;
$fecha_examen = (isset($_POST['fecha_examen']) && $_POST['fecha_examen']!=NULL)?SanitizeVars::DATE($_POST['fecha_examen']):false;

$fecha_examen_id = (isset($_POST['fecha_examen_id']) && $_POST['fecha_examen_id']!=NULL)?SanitizeVars::INT($_POST['fecha_examen_id']):false;

//die($fecha_examen_id." * ".$calendario_id." * ".$materia_id." * ".$llamado." * ".$fecha_examen);

$entidad = "Fecha de Exámen";

$array_resultados = array();

if ($calendario_id && $materia_id && $llamado && $fecha_examen) {
    $param = [];
    $obj = new MateriaFechaExamen();
    $param['idCalendario'] = $calendario_id;
    $param['idMateria'] = $materia_id;
    $param['llamado'] = $llamado;
    $param['fecha_examen'] = $fecha_examen;
        
    if ($fecha_examen_id) {
      $param['id'] = $fecha_examen_id;
    } 
    $res = $obj->save($param);
    if ($res==-1) {
            $array_resultados['codigo'] = 500;
            $array_resultados['alert'] = 'danger';
            $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos de la $entidad.";
    } else {
            $array_resultados['codigo'] = 200;
            $array_resultados['alert'] = 'success';
            $array_resultados['mensaje'] = "Los datos de la $entidad fueron Actualizados Exitosamente.";
    }

} else {
      $array_resultados['codigo'] = 500;
      $array_resultados['alert'] = 'danger';
      $array_resultados['mensaje'] = "Faltan datos obligatorios.";
}

echo json_encode($array_resultados);


?>
