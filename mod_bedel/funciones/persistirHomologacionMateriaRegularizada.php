<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$idCarrera = SanitizeVars::INT($_POST['carrera_id']);
$idMateria = SanitizeVars::INT($_POST['materia_id']);
$idAlumno = SanitizeVars::INT($_POST['alumno_id']);
$nota = SanitizeVars::INT($_POST['nota']);


$array_resultados = array();

$ahora = date("Y-m-d H:i:s");
$anioCursado = date('Y');
$fechaVencimientoRegularidad = (date('Y')+2).'-04-01';
if ($idAlumno && $idMateria && $idAlumno && $nota) {
    $sql = "INSERT INTO alumno_cursa_materia(idAlumno,idMateria,idTipoCursadoAlumno,anioCursado,tipo,FechaHoraInscripcion,nota,estado_final,FechaModificacionNota,FechaVencimientoRegularidad) " .
           "VALUES ($idAlumno,$idMateria,1,$anioCursado,'Presencial','$ahora',$nota,'Regularizo','$ahora','$fechaVencimientoRegularidad') ";
    $resultado = mysqli_query($conex,$sql);
    //die()
    if (mysqli_affected_rows($conex)!= -1) {
      $array_resultados['codigo'] = 100;
      $array_resultados['data'] = "La Homologación se ha realizado.";
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['data'] = "La Homologación NO se ha realizado.";
    }
} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['data'] = "Faltan Datos Obligatarios.";
}
echo json_encode($array_resultados);

?>
