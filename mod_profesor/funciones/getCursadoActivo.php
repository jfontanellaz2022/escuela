<?php
//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODOS LOS TIPOS DE CURSADO                                                   **
//***************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$array_resultados = array();
$sql = "SELECT e.*, ca.id as idCalendario, ca.fechaInicioEvento, ca.fechaFinalEvento
        FROM calendarioacademico ca, evento e
        WHERE ca.idEvento = e.id and
              (e.codigo = 1014 or e.codigo = 1015 or e.codigo = 1016) and
              CURDATE() between ca.fechaInicioEvento and ca.fechaFinalEvento
        ";

$resultado = mysqli_query($conex,$sql);

if ($resultado && mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existen Eventos Activos para armar el Listado de las materias.";
}

echo json_encode($array_resultados);

?>
