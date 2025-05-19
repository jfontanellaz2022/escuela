<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'seguridadNivel2.php';
require_once 'SanitizeCustom.class.php';
require_once 'Profesor.php';


$id_profesor = (isset($_POST['profesor']))?SanitizeVars::INT($_POST['profesor']):false;
//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 500;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}
//****************************************************** */

if ($id_profesor) {
   $objeto = new Profesor;
   $arr_carreras = $objeto->getAllCarrerasByProfesor($id_profesor);

   if (is_array($arr_carreras)) {
      $array_resultados['codigo'] = 200;
      $array_resultados['mensaje'] = "ok";
      $array_resultados['datos'] = $arr_carreras;
   } else {
      $array_resultados['codigo'] = 500;
      $array_resultados['mensaje'] = "Error 500: Hubo un error en la consulta.";
      $array_resultados['datos'] = [];
   }
} else {
   $array_resultados['codigo'] = 400;
   $array_resultados['mensaje'] = "Error 400: No ingreso el Profesor.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);







?>
