<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

//include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";


//****************************************** ARGUMENTOS ************************************/


$idAlumno = isset($_POST['alumno_id'])?SanitizeVars::INT($_POST['alumno_id']):false;
$idMateria = isset($_POST['materia_id'])?SanitizeVars::INT($_POST['materia_id']):false;
$anioCursado = isset($_POST['cursado_anio'])?SanitizeVars::INT($_POST['cursado_anio']):false;

$array_resultado = array();

$codigoCursado = isset($_POST['cursado_id'])?SanitizeVars::INT($_POST['cursado_id']):false;
$cursado = "";
if ($codigoCursado=='1') {
  $cursado = 'Presencial';
} else if ($codigoCursado=='2') {
  $cursado = 'Semipresencial';  
} else if ($codigoCursado=='3') {
  $cursado = 'Libre';  
};

$nota = isset($_POST['nota'])?SanitizeVars::INT($_POST['nota']):false;
$estado_final = isset($_POST['estado_final'])?SanitizeVars::STRING($_POST['estado_final']):false;
$fecha_vencimiento =isset($_POST['fecha_expiracion'])?SanitizeVars::DATE($_POST['fecha_expiracion']):false;
 

//****************************************** SENTENCIA SQL ************************************/



$sql = "UPDATE alumno_cursa_materia 
        SET idTipoCursadoAlumno = '$codigoCursado', tipo='$cursado', 
            nota=$nota, estado_final='$estado_final', FechaVencimientoRegularidad='$fecha_vencimiento'
        WHERE idMateria = $idMateria and idAlumno = $idAlumno and anioCursado = $anioCursado";

//die($sql);




$resultado = mysqli_query($conex,$sql);

// die($resultado);

if (mysqli_affected_rows($conex)) {
   $array_resultado['codigo'] = 100;
   $array_resultado['datos'] = 'correcto';
} else {
   $array_resultado['codigo'] = 11;
   $array_resultado['datos'] = "Error.";
}

echo json_encode($array_resultado);

?>