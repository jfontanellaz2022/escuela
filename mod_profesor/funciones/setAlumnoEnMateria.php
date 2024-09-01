<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$codigoCursadoForma = (isset($_POST['cursado']) && $_POST['cursado']!=NULL)?$_POST['cursado']:false;

$cursado = explode('-',$codigoCursadoForma);
$cursado_id = $cursado[0]; 
$cursado_codigo = $cursado[1]; 
$anioActual=date('Y');
$hoy=date('Y-m-d H:i:s');
$estado_final="";
$error_code_mysql = 0;

if ($cursado_codigo=='03') {
	  $estado_final='Libre';
	  $cursado_tipo = 'Libre';
} else if ($cursado_codigo=='02') {
	$estado_final='Cursando'; 
	$cursado_tipo = 'Semipresencial';
} else if ($cursado_codigo=='01') {
	$estado_final='Cursando'; 
	$cursado_tipo = 'Presencial';
}

$array_resultados = array();
if ($idMateria && $idAlumno && $codigoCursadoForma) {
	$sql = "INSERT INTO alumno_cursa_materia(idAlumno, idMateria, anioCursado, idTipoCursadoAlumno, tipo, FechaHoraInscripcion, nota, estado_final) values "
	       ."($idAlumno, $idMateria, $anioActual, $cursado_id, '$cursado_tipo', '$hoy', 0, '$estado_final')";
	try {
		mysqli_query($conex,$sql);
	} catch (Exception $e) {
		//echo 'Excepción capturada: ',  $e->getCode().': '.$e->getMessage(), "\n";
		$error_code_mysql = $e->getCode();
	}
    

	if ($error_code_mysql==0) {
				   $array_resultados['codigo'] = 100;
				   $array_resultados['data'] = "El alumno fue cargado exitosamente.";
	} else {
		if ($error_code_mysql == 1062) {
						  $array_resultados['codigo'] = 11;
						  $array_resultados['data'] = "El Alumno con ID <b>".$idAlumno."</b> ya se encuentra cargado en la materia.";
		} else {
						  $array_resultados['codigo'] = 12;
						  $array_resultados['data'] = "Hubo un Error en el Alta del Alumno a la Materia.";
		}
	}
} else {
		$array_resultados['codigo'] = 13;
		$array_resultados['data'] = "Faltan Datos para realizar la carga. ";
}
echo json_encode($array_resultados);
//die;

?>
