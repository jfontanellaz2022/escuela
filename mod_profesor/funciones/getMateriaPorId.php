<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;

$array_resultados = array();
$sql = "SELECT m.*, c.codigo as cursado_codigo, c.descripcion as cursado_descripcion,
               f.codigo as formato_codigo, f.descripcion as formato_descripcion, f.observacion as formato_observacion
        FROM materia m, cursado c, formato f
        WHERE m.id = $idMateria and
              m.idCursado = c.id and
              m.idFormato = f.id";
$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existe Materia.";
}

echo json_encode($array_resultados);
?>
