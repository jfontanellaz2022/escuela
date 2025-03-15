<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
require_once "SanitizeCustom.class.php";
require_once "Usuario.php";

$idUsuario = SanitizeCustom::INT($_POST['idUsuario']);
$nombre = SanitizeCustom::USUARIO($_POST['nombre']);
$captcha = SanitizeCustom::STRING($_POST['captcha']);

//var_dump($idUsuario,$nombre,$captcha);exit;
//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
$array_resultados = [];
if ($token!=$_SESSION['token']) {
  $array_resultados['codigo'] = 500;
  $array_resultados['class'] = 'danger';
  $array_resultados['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($array_resultados);exit;
}
//****************************************************** */


if (!$idUsuario) {
	$array_resultados['codigo'] = 500;
	$array_resultados['class'] = 'danger';
	$array_resultados['mensaje'] = "Faltan Datos obligatorios. ";
	echo json_encode($array_resultados);die;
};

if (!$nombre) {
	$array_resultados['codigo'] = 500;
	$array_resultados['class'] = 'danger';
	$array_resultados['mensaje'] = "No cumple con las reglas de nombre de Usuario. ";
	echo json_encode($array_resultados);die;
};

if (strtoupper($_SESSION['security_code'])!=strtoupper($captcha)) {
    $array_resultados['codigo'] = 500;
    $array_resultados['class'] = 'danger';
    $array_resultados['mensaje'] = 'El código de la imagen no coincide con el que ha ingresado.';
    echo json_encode($array_resultados);exit;
};


$obj = new Usuario();
$res = $obj->setNombre(["id"=>$idUsuario,"nombre"=>$nombre]);
//var_dump($res);exit;
if ($res==1) {
		$array_resultados['codigo'] = 200;
		$array_resultados['class'] = 'success';
        $array_resultados['mensaje'] = "El nombre de Usuario fue actualizado.";
} else if ($res==23000) {    
		$array_resultados['codigo'] = 500;
		$array_resultados['class'] = 'danger';
        $array_resultados['mensaje'] = "El nombre de Usuario ya está en uso.";
} else {  
		$array_resultados['codigo'] = 500;
		$array_resultados['class'] = 'danger';
        $array_resultados['mensaje'] = "Faltan Datos obligatorios.";
}


echo json_encode($array_resultados);

?>
