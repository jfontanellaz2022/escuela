<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
#require_once "seguridadNivel1.php";
require_once 'SanitizeCustom.class.php';
require_once 'Carrera.php';


//*******************TOKEN  *****************************/
/*$token = (isset($_GET['token']))?$_GET['token']:false;
if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 400;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}*/
//****************************************************** */

$objeto = new Carrera;
$arr_carreras = $objeto->getCarreras();

if (is_array($arr_carreras)) {
      $array_resultados['codigo'] = 200;
      $array_resultados['mensaje'] = "ok";
      $array_resultados['datos'] = $arr_carreras;
} else {
      $array_resultados['codigo'] = 400;
      $array_resultados['mensaje'] = "Hubo un error en la consulta.";
      $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);







?>
