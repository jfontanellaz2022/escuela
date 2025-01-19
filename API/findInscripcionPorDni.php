<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'../app/lib/controllers/');
define('ROOT_DIR1',realpath('../app/controllers'));
session_start();
require_once('ActasInscripcionCarreraPdf.class.php');
require_once(ROOT_DIR1 . '/ReporteController.php');
require_once('Parameters.php');
$dni = $_POST['dni'];
$carrera_id = $_POST['carrera'];
$codigo = $_POST['codigo'];
$respuesta = array();





if ($dni && strtoupper($codigo)==strtoupper($_SESSION['security_code'])) {
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