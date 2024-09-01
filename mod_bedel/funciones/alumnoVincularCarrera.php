<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

require_once "conexion.php";
require_once "Sanitize.class.php";
require_once "_seguridad.php";

$entidad = "Alumno";

$idAlumno = (isset($_POST['alumno_id']))?SanitizeVars::INT($_POST['alumno_id']):false;
$idCarrera = (isset($_POST['carrera_id']))?SanitizeVars::INT($_POST['carrera_id']):false;
$anio = date('Y');

//var_dump($_POST);die;
//die($accion.'-'.$apellido.'-'.$nombres.'-'.$dni.'-'.$domicilio.'-'.$telefono_caracteristica.'-'.$telefono_numero.'-'.$email.'-'.$localidad_id.'-'.$fecha_nacimiento);

$array_resultados = array();

if ($idAlumno && $idCarrera && $anio) {
      $sql_alumno_carrera = "INSERT alumno_estudia_carrera(idAlumno,idCarrera,anio) VALUES
                             ($idAlumno,$idCarrera,$anio)";
      $resultado = mysqli_query($conex,$sql_alumno_carrera);
      
      if (mysqli_errno($conex)=='1062') {
                  $array_resultados['codigo'] = 9;
                  $array_resultados['mensaje'] = "El $entidad con ID <strong>$idAlumno</strong> ya se encuentra vinculado a esa carrera.";  
                  
      } else if (mysqli_errno($conex)!='0') {
                  $array_resultados['codigo'] = 10;
                  $array_resultados['mensaje'] = "El $entidad con ID <strong>$idAlumno</strong> produjo un error.";  
      } else {
                  $array_resultados['codigo'] = 100;
                  $array_resultados['mensaje'] = "El $entidad fue vinculado a la carrera Exitosamente.";  
      }
};

echo json_encode($array_resultados);



?>
