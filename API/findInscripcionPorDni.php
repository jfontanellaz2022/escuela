<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'../app/lib/controllers/'.PATH_SEPARATOR.'./');
session_start();
define('ROOT_DIR1',realpath('../app/controllers'));
require_once 'SanitizeCustom.class.php';
require_once('ActasInscripcionCarreraPdf.class.php');
require_once(ROOT_DIR1 . '/ReporteController.php');
require_once('Parameters.php');
$dni = SanitizeCustom::DOCUMENTO_CUIL($_POST['dni'],8,8);
$carrera_id = SanitizeCustom::STRING($_POST['carrera']);
$codigo = SanitizeCustom::STRING($_POST['codigo']);
$token = SanitizeCustom::STRING($_POST['token'],3,50);

//var_dump($dni,$codigo,$carrera_id,$token,$_SESSION['security_code'],$_SESSION['token']);exit;
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
$respuesta = array();

if ($dni && $carrera_id && strtoupper($codigo)==strtoupper($_SESSION['security_code']) && $token==$_SESSION['token']) {
    $hoy = date('Y-m-d'); 
    $mes = date('m');
    $anio = date('Y');
    $url = Parameters::VALOR_URL;
    $anio_lectivo = "";

    if ($mes>7) {
        $anio_lectivo = $anio + 1;
    } else if ($mes<5) {
        $anio_lectivo = $anio;
    };

        
    $obj = new ReporteController();
    $arr_datos = $obj->getReporteInscripcion($dni,$anio_lectivo,$carrera_id);

    //var_dump($carrera_id,$arr_datos);exit;
   
    if (empty($arr_datos)) {
        $respuesta['codigo'] = '500';
        $respuesta['alert'] = 'danger';
        $respuesta['mensaje'] = "<strong>Error:</strong> No se ha encuentra Inscripcion con DNI <strong>$dni</strong>.";
    } else {
            $codificacion = base64_encode($anio_lectivo.'&'.$dni.'&'.$carrera_id);
            $url .= '/API/reporteInscripcionCarrera.php?p='. $codificacion;
            $respuesta['codigo'] = '200';
            $respuesta['alert'] = 'success';
            $respuesta['datos'] = $arr_datos;
            $respuesta['url'] = $url;
    };     
    
} else {
    $respuesta['codigo'] = '500';
    $respuesta['alert'] = 'danger';
    $respuesta['mensaje'] = "<strong>Error:</strong> El Código o el DNI es Inválido.";
}

echo json_encode($respuesta);

?>