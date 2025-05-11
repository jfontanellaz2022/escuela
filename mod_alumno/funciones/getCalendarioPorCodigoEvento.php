<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../lib');
//include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$codigo = (isset($_POST['codigo']) && $_POST['codigo']!=NULL)?SanitizeVars::INT($_POST['codigo']):false;
$array_resultados = array();

$anio_actual = date('Y');
$sql = "SELECT ca.*
        FROM calendarioacademico ca, evento e
        WHERE ca.AnioLectivo = $anio_actual and
              ca.idEvento = e.id and
              e.codigo = $codigo
        ";

$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existen Turnos.";
}

echo json_encode($array_resultados);

?>
