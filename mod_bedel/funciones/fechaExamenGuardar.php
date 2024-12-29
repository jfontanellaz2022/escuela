<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$accion = (isset($_POST['accion']) && $_POST['accion']!=NULL)?SanitizeVars::STRING($_POST['accion']):false;
$calendario_id = (isset($_POST['calendario_id']) && $_POST['calendario_id']!=NULL)?SanitizeVars::INT($_POST['calendario_id']):false;
$materia_id = (isset($_POST['materia_id']) && $_POST['materia_id']!=NULL)?SanitizeVars::INT($_POST['materia_id']):false;
$llamado = (isset($_POST['llamado']) && $_POST['llamado']!=NULL)?SanitizeVars::INT($_POST['llamado']):false;
$fecha_examen = (isset($_POST['fecha_examen']) && $_POST['fecha_examen']!=NULL)?SanitizeVars::DATE($_POST['fecha_examen']):false;

//die($anio_lectivo."&&".$evento."&&".$fecha_inicio."&&".$fecha_finalizacion);



$entidad = "Fecha de Exámen";
$array_resultados = array();

if ($accion=='editar') {
      $fecha_examen_id = (isset($_POST['fecha_examen_id']) && $_POST['fecha_examen_id']!=NULL)?SanitizeVars::INT($_POST['fecha_examen_id']):false;
      $sql = "UPDATE materia_tiene_fechaexamen
              SET fechaExamen = '$fecha_examen'
              WHERE id = $fecha_examen_id";
      //die($sql);        
      $resultado = mysqli_query($conex,$sql);
      $filas_afectadas = mysqli_affected_rows($conex);
                 
      if ($filas_afectadas!=-1) {
         $array_resultados['codigo'] = 100;
         $array_resultados['mensaje'] = "Los datos de la $entidad fueron Actualizados Exitosamente.";
      } else {
         $errorNro =  mysqli_errno($conex);
         $array_resultados['codigo'] = 12;
         $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos de la $entidad. ";
      }
} else if ($accion=='nuevo') {
      $sql = "INSERT INTO materia_tiene_fechaexamen (id, idCalendarioAcademico, idMateria, llamado, fechaExamen) VALUES 
              (NULL, $calendario_id, $materia_id, $llamado, '$fecha_examen')";
      //die($sql);
      $resultado = mysqli_query($conex,$sql);
      $filas_afectadas = mysqli_affected_rows($conex);

      if ($filas_afectadas>0) {
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = "El Registro fuero creado Exitosamente.";
      } else {
            $errorNro =  mysqli_errno($conex);
            $array_resultados['codigo'] = 12;
            $array_resultados['mensaje'] = "Hubo un Error en la creación de la $entidad. ";
      }  
};

echo json_encode($array_resultados);


?>
