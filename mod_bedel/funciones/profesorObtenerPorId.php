<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once 'verificarCredenciales.php';
require_once "Sanitize.class.php";
require_once "Profesor.php";

$id = ( isset($_POST['id']) )?SanitizeVars::INT($_POST['id']):false;

$array_resultados = array();
if ($id) {
    $objProfesor = new Profesor();
    $arr_datos_profesor = $objProfesor->getById($id);

    if ($arr_datos_profesor) {
        $array_resultados['codigo'] = 200;
        $array_resultados['alert'] = 'danger';
        $array_resultados['mensaje'] = "OK";
        $array_resultados['datos'] = $arr_datos_profesor;
    } else {
        $array_resultados['codigo'] = 500;
        $array_resultados['alert'] = 'danger';
        $array_resultados['mensaje'] = "El ID de Alumno es Incorrecto.";
    }

} else {
  $array_resultados['codigo'] = 500;
  $array_resultados['alert'] = 'danger';
  $array_resultados['mensaje'] = "El ID de Alumno es Incorrecto.";
}

echo json_encode($array_resultados);





?>
