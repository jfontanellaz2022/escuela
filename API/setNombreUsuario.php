<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once "Sanitize.class.php";
require_once "Usuario.php";

$idPersona = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$nombre = (isset($_POST['estado_nombre']) && $_POST['estado_nombre']!=NULL)?SanitizeVars::STRING($_POST['estado_nombre']):false;


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


if ($idPersona && $nombre) {

	$obj = new Usuario();
	$res = $obj->setNombre(["idPersona"=>$idPersona,"nombre"=>$nombre]);

	if ($res==true) {
		$array_resultados['codigo'] = 200;
        $array_resultados['mensaje'] = "La Nota Fue Cargada";
	} else if ($res=='23000') {    
		$array_resultados['codigo'] = 500;
        $array_resultados['mensaje'] = "El nombre de Usuario ya está en uso.";
	} else {  
		$array_resultados['codigo'] = 500;
        $array_resultados['mensaje'] = "Faltan Datos obligatorios.";
	}

} else {
		$array_resultados['codigo'] = 500;
		$array_resultados['mensaje'] = "Faltan Datos obligatorios. ";
}

echo json_encode($array_resultados);

?>
