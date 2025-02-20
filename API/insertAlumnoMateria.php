<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once "SanitizeCustom.class.php";
require_once "AlumnoCursaMateria.php";
require_once "Tipificacion.php";

$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$codigo_cursado = (isset($_POST['cursado']) && $_POST['cursado']!=NULL)?SanitizeVars::STRING($_POST['cursado']):false;

//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
$array_resultados = [];
if ($token!=$_SESSION['token']) {
  $array_resultados['codigo'] = 500;
  $array_resultados['class'] = 'danger';
  $array_resultados['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($array_resultados);die;
}
//****************************************************** */
$objCursado = new Tipificacion();

// 201 - Presencial | 202 - Semipresencial | 203 - Libre 
$datos_cursado = $objCursado->getTipificacionByCodigo($codigo_cursado);
$cursado_id = $datos_cursado['id'];
$cursado_nombre = $datos_cursado['nombre'];

// 01 - Cursando | 02 - Regularizo | 03 - Promociono | | 04 - Aprobo || 05 - Libre || 06 - suspenso 
$datos_estado = $objCursado->getTipificacionByCodigo('01');
$estado_id = $datos_estado['id'];
$estado_nombre = $datos_estado['nombre'];

//var_dump($datos_estado);exit;


$anio_actual = date('Y');

if ($idMateria && $idAlumno && $cursado_id) {
	$obj = new AlumnoCursaMateria();
	$res = $obj->save(['alumno_id'=>$idAlumno,"materia_id"=>$idMateria,"tipo"=>$cursado_nombre,"cursado_id"=>$cursado_id,
	                   'anio_cursado'=>$anio_actual,"estado_id"=>$estado_id, "nota"=>0.00]);
	if ($res && $res>0) {
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
