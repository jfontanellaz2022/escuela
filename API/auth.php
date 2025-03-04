<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/');
session_start();
require_once "SanitizeCustom.class.php";
require_once "Usuario.php";

$inputUsuario = SanitizeCustom::USUARIO($_POST['inputUsuario'],2,15);
$inputPassword = SanitizeCustom::PASSWDFLEX($_POST['inputPassword'],6,10);
$inputToken = SanitizeCustom::USUARIO($_POST['token'],3,50);

$finalResponse = array();
$_SESSION['arreglo_datos_usuario'] = $_SESSION['arreglo_credenciales_usuario'] = "";

//var_dump($inputUsuario . '**' . $inputPassword . '**' );die;

if ($inputToken!=$_SESSION['token']) {
      $finalResponse['codigo'] = 500;
      $finalResponse['class'] = 'danger';
      $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
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
      } else {
            $finalResponse['codigo'] = 500;
            $finalResponse['class'] = 'danger';
            $finalResponse['mensaje'] = "El Usuario/Password Incorrecta.";
      }
      
};

echo json_encode($finalResponse);

?>
