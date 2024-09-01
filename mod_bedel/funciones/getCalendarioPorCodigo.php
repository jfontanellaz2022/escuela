<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

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

/*
SELECT c.id, e.codigo, e.descripcion FROM calendarioacademico c, evento e WHERE c.idEvento = e.id and (e.codigo = 1000 or e.codigo = 1008 or e.codigo = 1005 or e.codigo = 1006 or e.codigo = 1007 or e.codigo = 1009 or e.codigo = 1010 or e.codigo = 1022);
*/

?>
