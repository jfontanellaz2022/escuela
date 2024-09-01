<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$idAlumno = (isset($_POST['alumno_id']))?SanitizeVars::INT($_POST['alumno_id']):false;
$idTipoCursado = (isset($_POST['tipo_cursado_id']))?SanitizeVars::INT($_POST['tipo_cursado_id']):false;
$nombreTipoCursado = (isset($_POST['nombre_tipo_cursado']) && (in_array($_POST['nombre_tipo_cursado'],['Presencial','Semipresencial','Libre'])) )?SanitizeVars::STRING($_POST['nombre_tipo_cursado']):false;
$nota = (isset($_POST['nota']) && $_POST['nota']>=-2 && $_POST['nota']<=10)?$_POST['nota']:false;
$estado_final = (isset($_POST['estado_final']) && in_array($_POST['estado_final'],['Regularizo','Libre','Promociono','Cursando','Homologo','Aprobo','Suspenso']))?$_POST['estado_final']:false;
$fecha_vencimiento = isset($_POST['fecha_vencimiento'])?SanitizeVars::FECHA($_POST['fecha_vencimiento']):false;

//die($idAlumno.'-'.$idTipoCursado.'-'.$nombreTipoCursado.'-'.$nota.'-'.$estado_final.'-'.$fecha_vencimiento);
//var_dump($_POST);die;

$subject = 'Cursado del Alumno';

/************************** Array de Resultados ***********************************/ 
// CODIGO DE RESULTADOS
// 100 - OK
//  12 - Error Consulta
//  13 - Faltan Datos Obligatorios

// ARRAY = [codigo,mensaje,datos] 

$array_resultados = array();

if ($idAlumno && $idTipoCursado && $nombreTipoCursado && $nota>=-2 && $nota<=10 && $estado_final && $fecha_vencimiento) {
      $sql = "UPDATE alumno_cursa_materia
              SET idTipoCursadoAlumno = $idTipoCursado, 
                  tipo='$nombreTipoCursado',
                  nota = '$nota',
                  estado_final = '$estado_final',
                  FechaVencimientoRegularidad = '$fecha_vencimiento'
              WHERE idAlumno = $idAlumno";
      //die($sql);        
      $resultado = mysqli_query($conex,$sql);
      if (mysqli_affected_rows($conex)==-1) {
         $errorNro =  mysqli_errno($conex);
         $array_resultados['codigo'] = 12;
         $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion del $subject. ";
         $array_resultados['datos'] = null;
      } else if (mysqli_affected_rows($conex)==0) {
            $errorNro =  mysqli_errno($conex);
            $array_resultados['codigo'] = 12;
            $array_resultados['mensaje'] = "El $subject No se ha actualizado. ";
            $array_resultados['datos'] = null;
      } else {
         $array_resultados['codigo'] = 100;
         $array_resultados['mensaje'] = "El $subject fue Actualizado Exitosamente.";
         $array_resultados['datos'] = null;
      }
} else {
      $array_resultados['codigo'] = 13;
      $array_resultados['mesnaje'] = "Faltan Datos para realizar la Actualizacion del $subject. ";
      $array_resultados['datos'] = null;
}

echo json_encode($array_resultados);



?>
