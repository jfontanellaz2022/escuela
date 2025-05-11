<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$carrera = (isset($_POST['carrera']) && $_POST['carrera']!=NULL)?SanitizeVars::INT($_POST['carrera']):false;
$array_resultados = array();
$sql = "SELECT a.*
        FROM alumno a, alumno_estudia_carrera aec
        WHERE aec.idCarrera = $carrera and
              aec.idAlumno = a.id and
              a.habilitado = 'Si'
        ORDER BY a.apellido asc, a.nombre asc
        ";
//die($sql);
$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existen Alumnos en la Carrera.";
}

echo json_encode($array_resultados);

?>
