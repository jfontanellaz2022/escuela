<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

//include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$entidad = 'Calendario';
$accion = (isset($_POST['accion']) && $_POST['accion']!=NULL)?SanitizeVars::STRING($_POST['accion']):false;
$id = (isset($_POST['id']) && $_POST['id']!=NULL)?SanitizeVars::INT($_POST['id']):false;
$anio_lectivo = (isset($_POST['anio']) && $_POST['anio']!=NULL)?SanitizeVars::INT($_POST['anio']):false;
$evento = (isset($_POST['evento']) && $_POST['evento']!=NULL)?SanitizeVars::INT($_POST['evento']):false;
$fecha_inicio = (isset($_POST['fecha_inicio']) && $_POST['fecha_inicio']!=NULL)?SanitizeVars::DATE($_POST['fecha_inicio']):false;
$fecha_finalizacion = (isset($_POST['fecha_finalizacion']) && $_POST['fecha_finalizacion']!=NULL)?SanitizeVars::DATE($_POST['fecha_finalizacion']):false;

//die($anio_lectivo."&&".$evento."&&".$fecha_inicio."&&".$fecha_finalizacion);

$array_resultados = array();

if ($accion=='editar') {
      $sql = "UPDATE calendarioacademico
              SET AnioLectivo=$anio_lectivo, 
                  fechaInicioEvento='$fecha_inicio',
                  fechaFinalEvento = '$fecha_finalizacion',
                  idEvento = $evento
              WHERE id = $id";

      $resultado = mysqli_query($conex,$sql);
      $filas_afectadas = mysqli_affected_rows($conex);
                 
      if ($filas_afectadas!=-1) {
         $array_resultados['codigo'] = 100;
         $array_resultados['mensaje'] = "Los datos del $entidad fueron Actualizados Exitosamente.";
      } else {
         $errorNro =  mysqli_errno($conex);
         $array_resultados['codigo'] = 12;
         $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del $entidad. ";
      }
} else if ($accion=='nuevo') {
      $sql = "INSERT INTO calendarioacademico(AnioLectivo,fechaInicioEvento,fechaFinalEvento,idEvento) VALUES
              ($anio_lectivo,'$fecha_inicio','$fecha_finalizacion',$evento)";

      $resultado = mysqli_query($conex,$sql);
      $filas_afectadas = mysqli_affected_rows($conex);

      if ($filas_afectadas>0) {
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = "El Registro fuero creado Exitosamente.";
      } else {
            $errorNro =  mysqli_errno($conex);
            $array_resultados['codigo'] = 12;
            $array_resultados['mensaje'] = "Hubo un Error en la creación del $entidad. ";
      }  
};

echo json_encode($array_resultados);


?>
