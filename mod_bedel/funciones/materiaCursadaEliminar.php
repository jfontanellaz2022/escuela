<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once "Sanitize.class.php";
require_once "AlumnoCursaMateria.php";

$accion = $_POST['accion'];
$id = SanitizeVars::INT($_POST['id']);

$array_resultados = array();

if ($accion=='Eliminar' && $id) {
    $alumno_rinde_materia = new AlumnoCursaMateria();
    $alumno_rinde_materia->deleteAlumnoCursaMateriaById($id);
    $array_resultados['codigo'] = 200;
    $array_resultados['datos'] = "Registro Eliminado!!!"; 

} else {
    $array_resultados['codigo'] = 500;
    $array_resultados['datos'] = "Faltan Datos Obligatorios.";

}

echo json_encode($array_resultados);

?>
