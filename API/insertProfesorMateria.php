<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');

require_once "seguridadNivel2.php";
require_once "Sanitize.class.php";
require_once "ProfesorDictaMateria.php";

$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$idProfesor = (isset($_POST['profesor']) && $_POST['profesor']!=NULL)?SanitizeVars::INT($_POST['profesor']):false;
$array_resultados = array();
if ($idMateria && $idProfesor ) {

	$obj = new ProfesorDictaMateria();
	$res = $obj->save(['profesor_id'=>$idProfesor,"materia_id"=>$idMateria]);

	if ($res) {
		$array_resultados['codigo'] = 200;
        $array_resultados['mensaje'] = "La materia fue vinculada.";
	} else {
		$array_resultados['codigo'] = 400;
        $array_resultados['mensaje'] = "Hubo un Error en la vinculacion del Profesor con la Materia.";
	}

} else {
		$array_resultados['codigo'] = 400;
		$array_resultados['mensaje'] = "Faltan Datos para realizar la carga. ";
}
echo json_encode($array_resultados);

?>
