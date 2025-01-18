<?php
//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODOS LOS DATOS DE LA CARRERA POR ID DE CARRERA                              **
//***************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idCarrera = (isset($_POST['carrera']) && $_POST['carrera']!=NULL)?SanitizeVars::INT($_POST['carrera']):false;
$array_resultados = array();

if ($idCarrera) {
      $sql = "SELECT c.*
              FROM carrera c
              WHERE c.id = $idCarrera;
              ";
      $resultado = mysqli_query($conex,$sql);
      if (mysqli_num_rows($resultado)>0) {
        $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
        $array_resultados['codigo'] = 100;
        $array_resultados['data'] = $filas;
      } else {
        $array_resultados['codigo'] = 11;
        $array_resultados['data'] = "No existe Carrera.";
      }
} else {
      $array_resultados['codigo'] = 10;
      $array_resultados['data'] = '[ID] de Carrera Inv&aacute;lido.';
};

echo json_encode($array_resultados);

?>
