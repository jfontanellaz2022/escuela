<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once "Sanitize.class.php";
require_once "AlumnoCursaMateria.php";

$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$nota = (isset($_POST['nota']) && $_POST['nota']!=NULL)?SanitizeVars::INT($_POST['nota']):false;
$estado = (isset($_POST['estado']) && $_POST['estado']!=NULL)?SanitizeVars::INT($_POST['estado']):false;
$estado_nombre = (isset($_POST['estado_nombre']) && $_POST['estado_nombre']!=NULL)?SanitizeVars::STRING($_POST['estado_nombre']):false;

$anioActual = date('Y');
$hoy = date('Y-m-d H:i:s');
$fechaVencimientoRegularidad = null;


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


if ($idMateria && $idAlumno && $estado_nombre) {

	if ($estado_nombre == 'Regularizo') {
		$anio_actual = date('Y')+4; 
		$fechaVencimientoRegularidad = $anio_actual.'-04-01';
	} else if ($estado_nombre == 'Libre') {
		$anio_actual = date('Y')+1; 
		$fechaVencimientoRegularidad = $anio_actual.'-04-01';
	};   

	$obj = new AlumnoCursaMateria();
	$res = $obj->save(["alumno_id"=>$idAlumno,"materia_id"=>$idMateria,"estado_id"=>$estado,
	                   "estado_nombre"=>$estado_nombre,"nota"=>$nota, "fecha_vencimiento_regularidad"=>$fechaVencimientoRegularidad]);

	if ($res) {
		$array_resultados['codigo'] = 200;
        $array_resultados['mensaje'] = "La Nota Fue Cargada";
	} else {
		$array_resultados['codigo'] = 500;
        $array_resultados['mensaje'] = "Hubo un Error.";
	}

} else {
		$array_resultados['codigo'] = 500;
		$array_resultados['mensaje'] = "Faltan Datos para realizar la carga. ";
}

echo json_encode($array_resultados);

?>
