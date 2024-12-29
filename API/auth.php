<?php
//Retornos de esta funcionalidad
// 1 (OK: Alumno)
// 2 (OK: Profesor)
// 3 (Error: El Usuario/Password/Perfil no coinciden.)
// 4 (Error: El Usuario, la Password o el Perfil No han sido ingresados.)
// 5 (Error: Problema con el Token.)
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/');
//die('sdfsdfsdsdf');
session_start();
require_once "SanitizeCustom.class.php";
require_once "Usuario.php";

$inputUsuario = SanitizeCustom::USUARIO($_POST['inputUsuario'],2,15);
$inputPassword = SanitizeCustom::USUARIO($_POST['inputPassword'],3,15);
$inputPerfil = SanitizeCustom::INT($_POST['inputPerfil']);
//var_dump($_POST);exit;
//var_dump($inputPerfil,$inputUsuario,$inputPassword);exit;

$finalResponse = array();
$_SESSION['arreglo_datos_usuario'] = $_SESSION['arreglo_credenciales_usuario'] = "";

//var_dump($inputUsuario . '**' . $inputPassword . '**' . $inputPerfil);die;
if (!$inputUsuario || !$inputPassword || !$inputPerfil ) {
      $finalResponse['codigo'] = 500;
      $finalResponse['class'] = 'danger';
      $finalResponse['mensaje'] = "Faltan Datos.";
} else {
      $objUsuario = new Usuario();
      //die('entroo');
      $res_auth = $objUsuario->autenticar($inputUsuario,$inputPassword);
      //var_dump($res_auth);exit;
      //exit;
      
      if (!empty($res_auth)) {
            $persona_id = $res_auth['idPersona'];
            $finalResponse['codigo'] = 200;
            $finalResponse['class'] = 'success';
            $finalResponse['mensaje'] = "Ok.";
            $finalResponse['datos'] = $inputPerfil;
            $idPersona = $res_auth['idPersona'];
            $_SESSION['arreglo_datos_usuario'] = $res_auth;
            $_SESSION['arreglo_credenciales_usuario'] = $objUsuario->getCredencialesByIdPersona($idPersona);
            //var_dump($_SESSION['arreglo_datos_usuario'],$_SESSION['arreglo_credenciales_usuario']);exit;
      } else {
            $finalResponse['codigo'] = 500;
            $finalResponse['class'] = 'danger';
            $finalResponse['mensaje'] = "El Usuario/Password Incorrecta.";
      }
      
};

echo json_encode($finalResponse);

?>
