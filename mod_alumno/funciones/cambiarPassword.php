<?php
session_start();
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'../../app/lib/');
//include_once 'seguridadNivel2.php';
require_once "conexion.php";
require_once "Usuario.php";


$dni = $_SESSION['dni'];
$tipo_usuario = $_SESSION['tipoUsuario'];

$contraseniaActual = $_POST['password_actual'];
$contraseniaNueva = $_POST['password_nueva'];
$contraseniaReNueva = $_POST['password_re_nueva'];

$array_resultados = array();
if ($contraseniaActual && $contraseniaNueva && $contraseniaReNueva) {
                $usuario = new Usuario();
                
                if (!empty($usuario->verificaPasswordByTipoByDni($tipo_usuario,$dni,$contraseniaActual))) {
                    $usuario->setPasswordByTipoByDni($tipo_usuario,$dni,$contraseniaNueva);
                    $array_resultados['codigo'] = 100;
                    $array_resultados['data'] = "La contrase&ntilde;a fue Modificada Exitosamente.";
                } else {
                    $array_resultados['codigo'] = 10;
                    $array_resultados['data'] = "La contrase&ntilde;a actual no coincide con la ingresada.";
                }

} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['data'] = "Existen datos sin completar.";
}

echo json_encode($array_resultados);
?>
