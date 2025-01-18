<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');
include_once 'seguridadNivel2.php';
include_once 'conexion.php';

$dni = $_SESSION['dni'];
$tipo_usuario = $_SESSION['tipoUsuario'];

$contraseniaActual = $_POST['password_actual'];
$contraseniaNueva = $_POST['password_nueva'];
$contraseniaReNueva = $_POST['password_re_nueva'];

$array_resultados = array();
if ($contraseniaActual && $contraseniaNueva && $contraseniaReNueva) {
                $sqlVerificaPassword="SELECT * 
                                      FROM usuario
                                      WHERE dni = $dni and 
                                            idtipo = $tipo_usuario and 
                                            pass = '$contraseniaActual'";
                $resultadoVerificaPassword =  mysqli_query($conex, $sqlVerificaPassword);
                
                if (mysqli_num_rows($resultadoVerificaPassword)>0) {
                        $sqlCambiarContrasenia = "UPDATE usuario 
                                                        SET pass='$contraseniaNueva', 
                                                            passwordVencida='N' 
                                                        WHERE dni='$dni' and idtipo='$tipo_usuario'";
                        $resultadoCambiarPassword =  mysqli_query($conex, $sqlCambiarContrasenia);
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
