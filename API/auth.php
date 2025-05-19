<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/');
session_start();
require_once "SanitizeCustom.class.php";
require_once "Usuario.php";

$inputUsuario = SanitizeCustom::USUARIOFLEX($_POST['inputUsuario'],3,15);
$inputUsuario = $_POST['inputUsuario'];
$inputPassword = SanitizeCustom::PASSWDFLEX($_POST['inputPassword'],6,10);
//$inputPassword = $_POST['inputPassword'];
$inputToken = SanitizeCustom::TOKEN($_POST['token'],3,5);

$finalResponse = array();
$_SESSION['arreglo_datos_usuario'] = $_SESSION['arreglo_credenciales_usuario'] = "";

//var_dump($inputUsuario . '**' . $inputPassword . '**' );die;

if ($inputToken!=$_SESSION['token']) {
      $finalResponse['codigo'] = 500;
      $finalResponse['class'] = 'danger';
      $finalResponse['mensaje'] = 'El Token es INCORRECTO.<p><strong>Actualice</strong> la página con la <strong>tecla F5</strong>.';
      echo json_encode($finalResponse);die;
}


if (!$inputUsuario || !$inputPassword ) {
      $finalResponse['codigo'] = 500;
      $finalResponse['class'] = 'danger';
      $finalResponse['mensaje'] = "Faltan Datos.";
} else {
      $objUsuario = new Usuario();
      $res_auth = $objUsuario->autenticar($inputUsuario,$inputPassword);
      if (!empty($res_auth)) {
            $persona_id = $res_auth['idRol'];
            $finalResponse['codigo'] = 200;
            $finalResponse['class'] = 'success';
            $finalResponse['mensaje'] = "Ok.";
            $finalResponse['datos'] = $res_auth['rol_descripcion'];
            $_SESSION['arreglo_datos_usuario'] = $res_auth;
            $_SESSION['arreglo_credenciales_usuario'] = $objUsuario->getCredencialesByIdPersona($persona_id);
            //var_dump($_SESSION['arreglo_datos_usuario'],"****",$_SESSION['arreglo_credenciales_usuario']);exit;
      } else {
            $finalResponse['codigo'] = 500;
            $finalResponse['class'] = 'danger';
            $finalResponse['mensaje'] = "El Usuario/Password Incorrecta.";
      }
      
};

echo json_encode($finalResponse);

?>
