<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'seguridadNivel2.php';
require_once 'AlumnoEstudiaCarrera.php';
require_once 'SanitizeCustom.class.php';

$id_alumno = (isset($_POST['alumno']))?SanitizeVars::INT($_POST['alumno']):false;

if ($id_alumno) {
   $objeto = new AlumnoEstudiaCarrera;
   $arr_carreras = $objeto->getAlumnoEstudiaCarreraByIdAlumno($id_alumno);

 
   $array_resultados['codigo'] = 200;
   $array_resultados['mensaje'] = "ok";
   $array_resultados['datos'] = $arr_carreras;
   
} else {
   $array_resultados['codigo'] = 400;
   $array_resultados['mensaje'] = "Error 400: No ingreso el Alumno.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);







?>
