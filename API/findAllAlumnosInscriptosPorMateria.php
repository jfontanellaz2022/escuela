<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
require_once "AlumnoRindeMateriaDetalle.php";
require_once "Sanitize.class.php";

$calendario_id = ( isset($_POST['calendario_id']) )?SanitizeVars::INT($_POST['calendario_id']):false;
$materia_id = ( isset($_POST['materia_id']) )?SanitizeVars::INT($_POST['materia_id']):false;
$llamado = ( isset($_POST['llamado']) )?SanitizeVars::INT($_POST['llamado']):3;
$token = (isset($_GET['token']))?$_GET['token']:false;

if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 500;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}

$arr_resultados = $arr_datos = [];
if ($materia_id && $calendario_id) {
    
    $alumnos_carrera = new AlumnoRindeMateriaDetalle();
    $arr_datos = $alumnos_carrera->getAlumnosByIdMateriaByIdCalendarioDetalle($materia_id,$calendario_id,$llamado);
    //var_dump($arr_datos);exit;
    $arr_datos_filtrados = [];
    //die('entro 2');
    foreach ($arr_datos as $val) {
      if ($val['condicion']!='Promocion') {
        $arr_datos_filtrados[] = $val;
      }
    }

    if (empty($arr_datos)) {
      $arr_resultados['codigo'] = 400;
      $arr_resultados['datos'] = [];
    } else {
      $arr_resultados['codigo'] = 200;
      $arr_resultados['datos'] = $arr_datos_filtrados;
    }
} else {
  $arr_resultados['codigo'] = 400;
  $arr_resultados['datos'] = "El ID del Registro es Incorrecto.";
}

echo json_encode($arr_resultados);

?>
