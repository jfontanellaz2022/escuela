<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

require_once 'AlumnoCursaMateria.php';
require_once 'Sanitize.class.php';
require_once "_seguridad.php";


$accion = $_POST['accion'];
$id = SanitizeVars::INT($_POST['id']);

$array_resultados = array();

if ($accion=='Eliminar' && $id) {
    $alumno_rinde_materia = new AlumnoCursaMateria();
    $alumno_rinde_materia->deleteAlumnoCursaMateriaById($id);
    $array_resultados['codigo'] = 100;
    $array_resultados['datos'] = "Registro Eliminado!!!"; 

} else {
    $array_resultados['codigo'] = 11;
    $array_resultados['datos'] = "Faltan Datos Obligatorios.";

}

echo json_encode($array_resultados);

?>
