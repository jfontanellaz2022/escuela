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

function getCodigoCalendarioHomologacion($conex) {
   $sql = "SELECT id FROM calendarioacademico WHERE idEvento = 1026";
   $resultado = mysqli_query($conex,$sql);
   $idCalendario = 0;
   if ($resultado) {
       $fila = mysqli_fetch_assoc($resultado);
       $idCalendario = $fila['id'];
   };
   return $idCalendario;
}


$idCalendario = getCodigoCalendarioHomologacion($conex);
$ahora = date("Y-m-d H:i:s");
if ($idAlumno && $idMateria && $idAlumno && $nota && $idCalendario) {
    $sql = "INSERT INTO alumno_rinde_materia(idAlumno,idMateria,idCalendario,llamado,condicion,FechaHoraInscripcion,nota,estado_final, FechaModificacionNota) " .
           "VALUES ($idAlumno,$idMateria,$idCalendario,1,'Homologacion','$ahora',$nota,'Aprobo','$ahora') ";
    $resultado = mysqli_query($conex,$sql);
    if (mysqli_affected_rows($conex)!=-1) {
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
