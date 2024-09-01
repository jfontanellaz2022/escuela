<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
require_once "_seguridad.php";

$dni = $_POST['dni'];
$contraseniaActual = $_POST['password_actual'];
$contraseniaNueva = $_POST['password_nueva'];
$contraseniaReNueva = $_POST['password_re_nueva'];

$array_resultados = array();
if ($contraseniaActual && $contraseniaNueva && $contraseniaReNueva) {
                $sqlVerificaPassword="SELECT * 
                                      FROM bedel
                                      WHERE dni = $dni and 
                                            password = '".md5($contraseniaActual)."'";
                $resultadoVerificaPassword =  mysqli_query($conex, $sqlVerificaPassword);
                
                if (mysqli_num_rows($resultadoVerificaPassword)>0) {
                        $sqlCambiarContrasenia = "UPDATE bedel 
                                                  SET password='".md5($contraseniaNueva)."' 
                                                      WHERE dni='$dni'";
                        $resultadoCambiarPassword =  mysqli_query($conex, $sqlCambiarContrasenia);
                        $array_resultados['codigo'] = 100;
                        $array_resultados['mensaje'] = "La contraseña fue Modificada Exitosamente.";
                } else {
                        $array_resultados['codigo'] = 10;
                        $array_resultados['mensaje'] = "La contraseña actual no coincide con la ingresada.";
                }
} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['mensaje'] = "Existen datos sin completar.";
}

echo json_encode($array_resultados);
?>
