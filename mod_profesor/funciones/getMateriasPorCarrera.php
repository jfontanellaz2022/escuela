<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idProfesor = (isset($_POST['profesor']) && $_POST['profesor']!=NULL)?SanitizeVars::INT($_POST['profesor']):false;
$idCarrera = (isset($_POST['carrera']) && $_POST['carrera']!=NULL)?SanitizeVars::INT($_POST['carrera']):false;

$array_resultados = array();
$sql = "SELECT m.*, tcm.id as idCursado, tcm.codigo as codigoCursado, tcm.descripcion as descripcion_cursado, f.descripcion as descripcion_formato, f.observacion
        FROM profesor_dicta_materia pdm, carrera_tiene_materia ctm, materia m, tipo_cursado_materia tcm, formato f
        WHERE pdm.idProfesor = $idProfesor and
              pdm.idMateria = ctm.idMateria and
              ctm.idCarrera = $idCarrera and
              ctm.idMateria = m.id and
              m.idCursado = tcm.id and
              m.idFormato = f.id
        ORDER BY m.anio asc, m.nombre asc";
$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existen Materias asociadas.";
}

echo json_encode($array_resultados);
?>
