<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'CalendarioAcademico.php';

$id = ( isset($_POST['id']) )?SanitizeVars::INT($_POST['id']):false;

$array_resultados = array();
if ($id) {
    $obj = new CalendarioAcademico();
    $arr_datos_evento = $obj->getCalendarioById($id);
    if (!$arr_datos_evento) {
        $array_resultados['codigo'] = 500;
        $array_resultados['mensaje'] = 'No existe Evento con el ID ' . $id;
        $array_resultados['class'] = 'danger';
    } else {
        $array_resultados['codigo'] = 200;
        $array_resultados['mensaje'] = 'OK';
        $array_resultados['class'] = 'success';
        $array_resultados['datos'] = $arr_datos_evento;
    }
    
} else {
  $array_resultados['codigo'] = 500;
  $array_resultados['mensaje'] = 'El ID de Calendario es Incorrecto.' ;
  $array_resultados['class'] = 'danger';
}

echo json_encode($array_resultados);

?>
