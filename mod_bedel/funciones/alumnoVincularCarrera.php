<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once "Sanitize.class.php";
require_once "AlumnoEstudiaCarrera.php";

$entidad = "Alumno";

$idAlumno = (isset($_POST['alumno_id']))?SanitizeVars::INT($_POST['alumno_id']):false;
$idCarrera = (isset($_POST['carrera_id']))?SanitizeVars::INT($_POST['carrera_id']):false;
$anio = (isset($_POST['anio']))?SanitizeVars::INT($_POST['anio']):false;
$fecha_inscripcion = date('Y-m-d');

$array_resultados = array();

if ($idAlumno && $idCarrera && $anio) {

      $objAEC = new AlumnoEstudiaCarrera();

      $param['idAlumno'] = $idAlumno;
      $param['idCarrera'] = $idCarrera;
      $param['anio'] = $anio;
      $param['mesa_especial'] = 'No';
      $param['fecha_inscripcion'] = $fecha_inscripcion;

      $res = $objAEC->save($param);

      if ($res) {
            $array_resultados['codigo'] = 200;
            $array_resultados['alert'] = 'success';
            $array_resultados['mensaje'] = "El $entidad con ID <strong>$idAlumno</strong> se vinculó a la carrera.";   
      } else {
            $array_resultados['codigo'] = 500;
            $array_resultados['alert'] = 'danger';
            $array_resultados['mensaje'] = "El $entidad con ID <strong>$idAlumno</strong> produjo un error.";  
      }
};

echo json_encode($array_resultados);



?>
