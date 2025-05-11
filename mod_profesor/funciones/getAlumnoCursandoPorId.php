<?php
//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODOS LOS ALUMNOS QUE CURSAN EN EL AÑO EN CURSO A UNA MATERIA POR ID MATERIA **
//***************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$array_resultados = array();

if ($idAlumno && $idMateria) {
      $anio_actual = date('Y');
      $sql = "SELECT a.*, acm.anioCursado, acm.tipo as cursado, acm.estado_final,
                    acm.FechaHoraInscripcion, acm.nota, acm.FechaModificacionNota, p.email, p.telefono
              FROM alumno a, alumno_cursa_materia acm, persona p
              WHERE acm.idMateria = $idMateria and
                    acm.idAlumno = a.id and
                    acm.anioCursado = $anio_actual and
                    a.id = $idAlumno and
                    a.dni = p.dni and
                    a.habilitado = 'Si'";
      $resultado = mysqli_query($conex,$sql);
      if (mysqli_num_rows($resultado)>0) {
        $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
        $array_resultados['codigo'] = 100;
        $array_resultados['data'] = $filas;
      } else {
        $array_resultados['codigo'] = 11;
        $array_resultados['data'] = "No existe Carrera.";
      }
} else {
  $array_resultados['codigo'] = 10;
  $array_resultados['data'] = '[ID] del Alumno Inv&aacute;lido.';
};

echo json_encode($array_resultados);

?>
