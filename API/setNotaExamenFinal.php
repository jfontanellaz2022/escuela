<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
include_once 'Sanitize.class.php';

require_once 'AlumnoRindeMateria.php';


$materia_id = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$alumno_id = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$nota = ( isset($_POST['nota']) && in_array($_POST['nota'],array(1,2,3,4,5,6,7,8,9,10,0,-1,-2)) )?$_POST['nota']:false;
$estado_final = (isset($_POST['estadoFinal']) && in_array($_POST['estadoFinal'],array('Pendiente','Aprobo','Ausente','Suspenso','Desaprobo')))?$_POST['estadoFinal']:false;
$calendario_id = (isset($_POST['calendario']) && $_POST['calendario']!=NULL)?SanitizeVars::INT($_POST['calendario']):false;
$llamado = (isset($_POST['llamado']) && $_POST['llamado']!=NULL)?SanitizeVars::INT($_POST['llamado']):false;
$condicion = (isset($_POST['condicion']) && $_POST['condicion']!=NULL)?$_POST['condicion']:'Regular';

//var_dump($materia_id,$alumno_id,$nota,$estado_final,$calendario_id,$llamado);exit;

$hoy=date('Y-m-d H:i:s');

//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
$array_resultados = [];
if ($token!=$_SESSION['token']) {
  $array_resultados['codigo'] = 500;
  $array_resultados['class'] = 'danger';
  $array_resultados['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($array_resultados);die;
}
//****************************************************** */
if ($materia_id && $alumno_id && $nota && $estado_final && $calendario_id && $llamado) {
      $arm = new AlumnoRindeMateria();
      $id = $arm->getIdCalendario(['materia_id'=>$materia_id,'alumno_id'=>$alumno_id,
                                   'calendario_id'=>$calendario_id,'llamado'=>$llamado])['id'];

      
      $arm->save(['id'=>$id,'materia_id'=>$materia_id,'alumno_id'=>$alumno_id,
                  'calendario_id'=>$calendario_id,'llamado'=>$llamado,
                  'nota'=>$nota,'condicion'=>$condicion,'estado_final'=>$estado_final,
                  'fecha_modificacion_nota'=>$hoy]);


      $arr_resultados['codigo'] = 200;
      $arr_resultados['class'] = "danger";
      $arr_resultados['mensaje'] = "El Registro fue Actualizado Correctamente.";
          
} else {
      $arr_resultados['codigo'] = 500;
      $arr_resultados['class'] = "danger";
      $arr_resultados['mensaje'] = "Faltan Datos para realizar la carga. ";
}
echo json_encode($arr_resultados);



?>
