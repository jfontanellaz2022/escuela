<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib');
require_once('Carrera.php');
require_once 'SanitizeCustom.class.php';
//include_once 'seguridadNivel2.php';

$id_carrera = (isset($_POST['carrera']))?SanitizeCustom::INT($_POST['carrera']):false;
$array_resultados = [];

if ($id_carrera) {
      $objeto = new Carrera;
      $arr_datos_carrera = $objeto->getCarreraById($id_carrera);

      if (is_array($arr_datos_carrera)) {
            $array_resultados['codigo'] = 200;
            $array_resultados['mensaje'] = "ok";
            $array_resultados['datos'] = $arr_datos_carrera;
      } else {
            $array_resultados['codigo'] = 500;
            $array_resultados['mensaje'] = "Error 500: Hubo un error en la consulta.";
            $array_resultados['datos'] = [];
      }
} else {
      $array_resultados['codigo'] = 400;
      $array_resultados['mensaje'] = "Error 400: No ingreso el ID de la carrera.";
      $array_resultados['datos'] = [];
}
echo json_encode($array_resultados);







?>
