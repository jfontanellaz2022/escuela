<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$array_resultados = array();

$sql = "SELECT ca.id as 'idCalendario', ca.AnioLectivo as 'anio_lectivo', ca.fechaInicioEvento as 'fecha_inicio', 
               ca.fechaFinalEvento as 'fecha_final', e.id as 'idEvento', e.codigo as 'codigo', e.descripcion as 'descripcion'
        FROM calendarioacademico ca, evento e 
        WHERE ca.idEvento = e.id and 
              (e.codigo=1005 or e.codigo = 1006 or e.codigo = 1007 or e.codigo = 1008) and 
               ca.AnioLectivo = year(now()) 
        ORDER BY ca.id DESC 
        LIMIT 0,1";

$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existen Inscripciones.";
}

echo json_encode($array_resultados);



?>
