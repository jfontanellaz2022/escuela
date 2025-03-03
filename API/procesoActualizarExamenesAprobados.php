<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once "Sanitize.class.php";
require_once "AlumnoRindeMateria.php";

$idCalendario = (isset($_POST['calendario']) && $_POST['calendario']!=NULL)?SanitizeVars::INT($_POST['calendario']):false;

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

if ($idCalendario) {

	$obj = new AlumnoRindeMateria();
	$res = $obj->procesoActualizarNotasExamenes($idCalendario);
	//var_dump($res);exit;
	if ($res) {
		$array_resultados['codigo'] = 200;
		$array_resultados['class'] = 'success';
        $array_resultados['mensaje'] = "Los Examenes fueron actualizados.";
	} else {
		$array_resultados['codigo'] = 500;
		$array_resultados['class'] = 'danger';
        $array_resultados['mensaje'] = "No se ha realizado la actualización.";
	}

} else {
		$array_resultados['codigo'] = 500;
		$array_resultados['class'] = 'danger';
		$array_resultados['mensaje'] = "Faltan Datos para la actualización. ";
}

echo json_encode($array_resultados);

?>
