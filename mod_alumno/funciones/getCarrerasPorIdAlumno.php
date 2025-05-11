<?php
//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODAS LAS CARRERAS QUE EN LAS QUE DICTA CLASES UN PROFESOR POR ID PROFESOR   **
//***************************************************************************************
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once 'AlumnoEstudiaCarrera.php';
require_once 'Sanitize.class.php';

$idAlumno = $_SESSION['idAlumno'];
$arr_resultados = [];

$aec = new AlumnoEstudiaCarrera();
$arr_carreras = $aec->getAlumnoEstudiaCarreraByIdAlumno($idAlumno);

if (!empty($arr_carreras)) {
   $arr_resultados['codigo'] = 100;
   $arr_resultados['datos'] = $arr_carreras;
} else {
  $arr_resultados['codigo'] = 11;
  $arr_resultados['datos'] = "No existen Carreras asociadas.";
}
echo json_encode($arr_resultados);

?>
