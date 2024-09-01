<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$idCarrera = SanitizeVars::INT($_POST['carrera_id']);

$array_resultados = array();

if ($idCarrera) {
    $sql = "SELECT a.*
            FROM alumno_estudia_carrera aec, alumno a 
            WHERE aec.idCarrera = $idCarrera AND
                  aec.idAlumno = a.id
            ORDER BY apellido asc, nombre asc";
    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) {
      $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
      $array_resultados['codigo'] = 100;
      $array_resultados['datos'] = $filas;
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['datos'] = "No existe la Carrera.";
    }
} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['datos'] = "Faltan Datos Obligatarios.";
}
echo json_encode($array_resultados);

?>
