<?php
//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODAS LAS CARRERAS QUE EN LAS QUE DICTA CLASES UN PROFESOR POR ID PROFESOR   **
//***************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

//die('sdfsdfsdf');
$idProfesor = (isset($_POST['profesor']) && $_POST['profesor']!=NULL)?SanitizeVars::INT($_POST['profesor']):false;

$array_resultados = array();
$sql = "SELECT c.id, c.descripcion,c.habilitada, c.imagen, p.id as idProfesor
        FROM profesor p, profesor_pertenece_carrera ppc, carrera c
        WHERE p.id='$idProfesor' and p.id = ppc.idProfesor and ppc.idCarrera = c.id;
        ";

$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existen Carreras asociadas.";
}

echo json_encode($array_resultados);

?>
