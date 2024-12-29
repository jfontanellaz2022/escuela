<?php

set_include_path('../../app/models/v1/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../'.PATH_SEPARATOR.'../../conexion/');

require_once 'verificarCredenciales.php';
require_once 'conexion.php';
require_once "Sanitize.class.php";


$id = ( isset($_POST['id']) )?SanitizeVars::INT($_POST['id']):false;

$array_resultados = array();
if ($id) {
    $sql = "SELECT per.*, l.id as 'localidad_id', l.nombre as 'localidad_nombre', l.cp as 'codigo_postal', p.nombre as 'provincia_nombre',
                   a.anio_ingreso, a.debe_titulo
            FROM alumno a, persona per, localidad l, provincia p 
            WHERE a.id = $id AND a.idPersona = per.id AND per.idLocalidad = l.id AND l.provincia_id = p.id";
    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) {
      $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
      $array_resultados['codigo'] = 100;
      $array_resultados['datos'] = $filas;
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['datos'] = "No existe Alumno.";
    }
} else {
  $array_resultados['codigo'] = 10;
  $array_resultados['datos'] = "El ID de Alumno es Incorrecto.";
}

echo json_encode($array_resultados);

?>
