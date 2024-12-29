
<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";


//****************************************** ARGUMENTOS ************************************/


$idAlumno = isset($_POST['alumno_id'])?SanitizeVars::INT($_POST['alumno_id']):false;
$idMateria = isset($_POST['materia_id'])?SanitizeVars::INT($_POST['materia_id']):false;
$idCalendario = isset($_POST['calendario_id'])?SanitizeVars::INT($_POST['calendario_id']):false;
$llamado = isset($_POST['llamado'])?SanitizeVars::INT($_POST['llamado']):false;

$array_resultado = array();

$condicion = isset($_POST['condicion'])?SanitizeVars::STRING($_POST['condicion']):false;
$nota = isset($_POST['nota'])?SanitizeVars::INT($_POST['nota']):false;
$estado_final = isset($_POST['estado_final'])?SanitizeVars::STRING($_POST['estado_final']):false;
 

//****************************************** SENTENCIA SQL ************************************/



$sql = "UPDATE alumno_rinde_materia 
        SET condicion = '$condicion',
            nota=$nota, 
            estado_final='$estado_final'
        WHERE idMateria = $idMateria and idAlumno = $idAlumno and idCalendario = $idCalendario and llamado=$llamado";

//die($sql);

$resultado = mysqli_query($conex,$sql);

if (mysqli_affected_rows($conex)) {
   $array_resultado['codigo'] = 100;
   $array_resultado['datos'] = 'correcto';
} else {
  $array_resultado['codigo'] = 11;
  $array_resultado['datos'] = "Error.";
}

echo json_encode($array_resultado);

?>