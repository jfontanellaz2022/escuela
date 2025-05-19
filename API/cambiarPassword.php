<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');

require_once "seguridadNivel1.php";
require_once "Persona.php";
require_once "Usuario.php";
include_once 'SanitizeCustom.class.php';


function rellenar($val) {
    $cantidad_digitos = strlen($val."");
    $cantidad_ceros = 6 - $cantidad_digitos;
    $str_ceros = "";
    
    for($i=0;$i<$cantidad_ceros;$i++) {
        $str_ceros .= '0';
    }
    
    $valor_final = $str_ceros.$val;
    return $valor_final;
    
}

function generarCodigo() {
    $val = rand(1,999999);
    return rellenar($val);
}


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


// ********************************************************************************
// ****************************** MAIN ********************************************
// ********************************************************************************

$password = SanitizeCustom::PASSWD($_POST['password']);
$re_password = SanitizeCustom::PASSWD($_POST['repassword']);
$captcha = SanitizeCustom::TOKEN($_POST['captcha']);

//var_dump($password,$re_password,$_POST['captcha'],$captcha,$_SESSION['security_code']);exit;

$email = $_SESSION['arreglo_datos_usuario']['email'];
$idPersona = $_SESSION['arreglo_datos_usuario']['idPersona'];
$idUsuario = $_SESSION['arreglo_datos_usuario']['id'];

if ($password!=$re_password) {
    $array_resultados['codigo'] = 500;
    $array_resultados['class'] = 'danger';
    $array_resultados['mensaje'] = 'La contraseña no coincide con la que ha repetido.';
    echo json_encode($array_resultados);die;
}

if (!$password) {
    $array_resultados['codigo'] = 500;
    $array_resultados['class'] = 'danger';
    $array_resultados['mensaje'] = 'La contraseña no cumple con las reglas exigidas. Ésta debe tener entre 6 y 10 caracteres, estar formada por letras, al menos un número y al menos un caracter especial como los siguientes: <strong>#_@*-$.&</strong>';
    echo json_encode($array_resultados);die;
} 

if (strtoupper($_SESSION['security_code'])!=strtoupper($captcha)) {
    $array_resultados['codigo'] = 500;
    $array_resultados['class'] = 'danger';
    $array_resultados['mensaje'] = 'El código de la imagen no coincide con el que ha ingresado.';
    echo json_encode($array_resultados);die;
}

if ($email && $idPersona && $idUsuario) {
     $objusu = new Usuario();
     $res = $objusu->setPasswordById($idUsuario,$password);
     
     if ($res) {
                $arr_resultado['codigo'] = 200;
                $arr_resultado['mensaje'] = 'La Clave ha sido actualizada exitosamente.';
                $arr_resultado['class'] = 'success';
                $para      = $email;
                $titulo    = 'Modificación de Clave de Ingreso';
                $imageUrl = "https://escuela40.net/public/img/encabezado_ens40_1.jpeg";
                $mensaje = "
                        <html>
                        <head>
                          <title>Clave nueva</title>
                          <style>
                            .header {
                              font-size: 24px;
                              font-weight: bold;
                              color: #333;
                              text-align: center;
                              margin-top: 20px;
                            }
                            .code {
                              font-size: 28px;
                              font-weight: bold;
                              color: #4CAF50;
                              text-align: center;
                            }
                            .content {
                              font-size: 16px;
                              color: #555;
                              text-align: center;
                            }
                            .container {
                              width: 100%;
                              max-width: 600px;
                              margin: 0 auto;
                              padding: 20px;
                              border: 1px solid #ddd;
                              border-radius: 8px;
                            }
                            .image {
                              width: 100%;
                              height: auto;
                            }
                          </style>
                        </head>
                        <body>
                          <div class='container'>
                            <img src='$imageUrl' alt='Encabezado' class='image'>
                            <p class='header'>La clave fue modificada.</p>
                            <p class='content'>Hola, te queremos dar aviso de que <strong>tu clave fue modificada</strong>.</p>
                            <p class='content'>En el caso que no haya sido usted, comuniquese al siguiente email: <a href='mailto:jfontanellaz@gmail.com'>jfontanellaz@gmail.com</a>.</p>
                            <p class='content'>Saludos,<br>Tu equipo de soporte</p>
                          </div>
                        </body>
                        </html>
                        ";
                                            
                // Encabezados para enviar correo en formato HTML
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: soporte@escuela40.net" . "\r\n";
                mail($para, $titulo, $mensaje, $headers);
            } else {
                $arr_resultado['codigo'] = 500;
                $arr_resultado['mensaje'] = 'Ocurrio un Error.';
                $arr_resultado['class'] = 'danger';
            }



} else {
    $arr_resultado['codigo'] = 500;
    $arr_resultado['mensaje'] = 'No tiene Email.';
    $arr_resultado['class'] = 'danger';
}

echo json_encode($arr_resultado);

?>