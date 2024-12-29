<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$fecha_examen_id = (isset($_POST['fecha_examen_id']) && $_POST['fecha_examen_id']!=NULL)?SanitizeVars::INT($_POST['fecha_examen_id']):false;
$array_resultados = array();

if ($fecha_examen_id) {
    $sql = "SELECT mtf.id as 'fecha_examen_id', mtf.idCalendarioAcademico as 'calendario_id', mtf.idMateria as 'materia_id', m.nombre as 'materia_nombre', mtf.llamado, mtf.fechaExamen as 'fecha_examen'
            FROM materia_tiene_fechaexamen mtf, materia m 
            WHERE mtf.id=$fecha_examen_id and mtf.idMateria = m.id";
    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) {
      $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
      $array_resultados['codigo'] = 100;
      $array_resultados['data'] = $filas;
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['data'] = "No existe la Fecha de Examen.";
    }
} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['data'] = "Faltan Datos Obligatorios.";
}
echo json_encode($array_resultados);

?>
