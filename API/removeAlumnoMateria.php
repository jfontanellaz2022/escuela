<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
	require_once 'SanitizeCustom.class.php';
	require_once "AlumnoCursaMateria.php";
	
	$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
	$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
	$anio = (isset($_POST['anio']) && $_POST['anio']!=NULL)?SanitizeVars::INT($_POST['anio']):false;

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
	if ($idAlumno && $idMateria && $anio) {
		$objPDM = new AlumnoCursaMateria(); 
		$objPDM->deleteAlumnoCursaMateriaByIdAlumnoByIdMateriaByAnio($idAlumno,$idMateria,$anio);
		$array_resultados['codigo'] = 200;
      	$array_resultados['class'] = "success";
      	$array_resultados['mensaje'] = "El Alumno fue desviculada de la Materia exitosamente.";
      	$array_resultados['datos'] = [];
	} else {
		$array_resultados['codigo'] = 400;
      	$array_resultados['class'] = "danger";
      	$array_resultados['mensaje'] = "El Alumno NO se podido desvincularse de la Materia.";
      	$array_resultados['datos'] = [];	
	}
	echo json_encode($array_resultados);

?>
