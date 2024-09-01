<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;

$anioActual=date('Y');


$array_resultados = array();
if ($idMateria && $idAlumno) {
	$sql = "DELETE FROM alumno_cursa_materia
		    WHERE idAlumno = $idAlumno and
			      idMateria = $idMateria and
				  anioCursado = $anioActual";
    $resultado = mysqli_query($conex,$sql);
	if ($resultado) {
 	    $array_resultados['codigo'] = 100;
	    $array_resultados['data'] = "El alumno fue Eliminado exitosamente.";
	} else {
	    $array_resultados['data'] = "Hubo un Error en la eliminaci&oacute;n del Alumno en la Materia.";
	}
} else {
		$array_resultados['codigo'] = 13;
		$array_resultados['data'] = "Faltan Datos para realizar la eliminaci&oacute;n. ";
}
echo json_encode($array_resultados);

?>
