<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$array_resultados = array();

$sql = "SELECT id, codigo, descripcion, descripcion_corta, habilitada, imagen
        FROM carrera 
        WHERE habilitacion_cursado = 'Si'
        ORDER BY descripcion ASC";
$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
      $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
      $array_resultados['codigo'] = 100;
      $array_resultados['data'] = $filas;
} else {
      $array_resultados['codigo'] = 11;
      $array_resultados['data'] = "No existe la Carrera.";
}
echo json_encode($array_resultados);

?>
