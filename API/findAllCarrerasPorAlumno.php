<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
require_once 'AlumnoEstudiaCarrera.php';
require_once 'SanitizeCustom.class.php';

$id_alumno = (isset($_POST['alumno']))?SanitizeVars::INT($_POST['alumno']):false;
//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 500;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}
//****************************************************** */

if ($id_alumno) {
   $objeto = new AlumnoEstudiaCarrera();
   //var_dump('acaaaaa');exit;
   $arr_carreras = $objeto->getAlumnoEstudiaCarreraByIdAlumno($id_alumno);

 
   $array_resultados['codigo'] = 200;
   $array_resultados['mensaje'] = "ok";
   $array_resultados['datos'] = $arr_carreras;
   
} else {
   $array_resultados['codigo'] = 400;
   $array_resultados['mensaje'] = "Error 400: No ingreso el Alumno.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);







?>
