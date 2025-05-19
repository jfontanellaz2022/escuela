<?php
//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODOS LOS ALUMNOS DE UN ALUMNO POR ID ALUMNO                                 **
//***************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$array_resultados = array();

if ($idAlumno) {
      $sql = "SELECT a.*
              FROM alumno a
              WHERE a.id = $idAlumno;
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
  $array_resultados['data'] = '[ID] del Alumno Inv&aacute;lido.';
};

echo json_encode($array_resultados);

?>
