<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idCarrera = (isset($_POST['carrera']) && $_POST['carrera']!=NULL)?SanitizeVars::INT($_POST['carrera']):false;

$array_resultados = array();
if ($idCarrera) {
    $sql = "SELECT m.*
            FROM carrera_tiene_materia ctm, materia m
            WHERE ctm.idCarrera = $idCarrera and
                  m.id = ctm.idMateria 
            ORDER BY m.anio asc, m.nombre asc";
    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) {
      $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
      $array_resultados['codigo'] = 100;
      $array_resultados['data'] = $filas;
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['data'] = "No existen Materias asociadas.";
    }
} else {
  $array_resultados['codigo'] = 10;
  $array_resultados['data'] = "Falta un datos obligatorio.";
}

echo json_encode($array_resultados);
?>
