<?php
//*******************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                     **
//** SACA TODOS LOS ALUMNOS DE UN CALENDARIO DE INSCRIPCION Y EN UN LLAMADO A EXAMEN POR ID ALUMNO     **
//*******************************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';


$materia = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$idInscripcion = (isset($_POST['calendario']))?SanitizeVars::INT($_POST['calendario']):false;
$llamado = (isset($_POST['llamado']) && in_array($_POST['llamado'],[1,2]))?SanitizeVars::INT($_POST['llamado']):false;
$array_resultados = array();


$sql = "SELECT DISTINCT a.*, arm.condicion, arm.FechaHoraInscripcion, arm.FechaModificacionNota,
               arm.nota, arm.estado_final, p.email, p.telefono
        FROM alumno a, alumno_rinde_materia arm, persona p, alumno_cursa_materia acm
        WHERE arm.idMateria = $materia and
              arm.llamado = $llamado and
              arm.idCalendario = $idInscripcion and
              arm.idAlumno = a.id and
              a.id = acm.idAlumno and
			  arm.condicion<>'Promocion' and 
              a.dni = p.dni
              ";
//die($sql);              
$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
   $array_resultados['llamado'] = $llamado;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existen Alumnos que rindan la materia.";
}

echo json_encode($array_resultados);

?>
