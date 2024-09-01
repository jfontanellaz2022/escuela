<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once "SanitizeCustom.class.php";
require_once "AlumnoCursaMateria.php";
require_once "AlumnoTipoCursado.php";

$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$codigo_cursado = (isset($_POST['cursado']) && $_POST['alumno']!=NULL)?SanitizeVars::STRING($_POST['cursado']):false;


$array_resultados = array();

$objCursado = new AlumnoTipoCursado();
$datos_cursado = $objCursado->getAlumnoTipoCursadoByCodigo($codigo_cursado);
$cursado_id = $datos_cursado['id'];
$cursado_nombre = $datos_cursado['nombre'];
$anio_actual = date('Y');

if ($idMateria && $idAlumno && $cursado_id) {
	

	$obj = new AlumnoCursaMateria();
	$res = $obj->save(['alumno_id'=>$idAlumno,"materia_id"=>$idMateria,"tipo"=>$cursado_nombre,"cursado_id"=>$cursado_id,'anio_cursado'=>$anio_actual]);

	if ($res) {
		$array_resultados['codigo'] = 200;
        $array_resultados['mensaje'] = "El Alumno fue vinculado a la materia.";
	} else {
		$array_resultados['codigo'] = 400;
        $array_resultados['mensaje'] = "Hubo un Error en la vinculacion del Alumno con la Materia.";
	}

} else {
		$array_resultados['codigo'] = 400;
		$array_resultados['mensaje'] = "Faltan Datos para realizar la carga. ";
}
echo json_encode($array_resultados);

?>
