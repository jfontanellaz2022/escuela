<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$idCalendario = (isset($_POST['calendario']) && $_POST['calendario']!=NULL)?SanitizeVars::INT($_POST['calendario']):false;
$llamado = (isset($_POST['llamado']) && $_POST['llamado']!=NULL)?SanitizeVars::INT($_POST['llamado']):false;


$array_resultados = array();
$sql = "SELECT fechaExamen
        FROM materia_tiene_fechaexamen
        WHERE idCalendarioAcademico = $idCalendario and
              idMateria = $idMateria and
              llamado = $llamado";
$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
   //die('entroooo fecha');
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existe llamado.";
}

echo json_encode($array_resultados);
?>
