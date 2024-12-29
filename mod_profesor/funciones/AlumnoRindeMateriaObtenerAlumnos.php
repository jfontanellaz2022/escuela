<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'./');

require_once "AlumnoRindeMateriaDetalle.php";
require_once "Sanitize.class.php";
require_once "_seguridad.php";

$calendario_id = ( isset($_POST['calendario_id']) )?SanitizeVars::INT($_POST['calendario_id']):false;
$materia_id = ( isset($_POST['materia_id']) )?SanitizeVars::INT($_POST['materia_id']):false;
$llamado = ( isset($_POST['llamado']) )?SanitizeVars::INT($_POST['llamado']):false;
$arr_resultados = $arr_datos = [];

if ($materia_id && $calendario_id) {
    $profesor_carrera = new AlumnoRindeMateriaDetalle();
    $arr_datos = $profesor_carrera->getAlumnosByIdMateriaByIdCalendarioDetalle($materia_id,$calendario_id,$llamado);
    $arr_datos_filtrados = [];

    foreach ($arr_datos as $val) {
      
      if ($val['condicion']!='Promocion') {
        $arr_datos_filtrados[] = $val;
      }
    }

    if (empty($arr_datos)) {
      $arr_resultados['codigo'] = 100;
      $arr_resultados['datos'] = [];
    } else {
      $arr_resultados['codigo'] = 100;
      $arr_resultados['datos'] = $arr_datos_filtrados;
    }
} else {
  $arr_resultados['codigo'] = 10;
  $arr_resultados['datos'] = "El ID del Registro es Incorrecto.";
}

echo json_encode($arr_resultados);

?>
