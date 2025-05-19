<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once "CalendarioAcademico.php";

$entidad = 'Calendario';
$accion = (isset($_POST['accion']) && $_POST['accion']!=NULL)?SanitizeVars::STRING($_POST['accion']):false;
$id = (isset($_POST['id']) && $_POST['id']!=NULL)?SanitizeVars::INT($_POST['id']):false;
$anio_lectivo = (isset($_POST['anio']) && $_POST['anio']!=NULL)?SanitizeVars::INT($_POST['anio']):false;
$evento = (isset($_POST['evento']) && $_POST['evento']!=NULL)?SanitizeVars::INT($_POST['evento']):false;
$fecha_inicio = (isset($_POST['fecha_inicio']) && $_POST['fecha_inicio']!=NULL)?SanitizeVars::DATE($_POST['fecha_inicio']):false;
$fecha_final = (isset($_POST['fecha_finalizacion']) && $_POST['fecha_finalizacion']!=NULL)?SanitizeVars::DATE($_POST['fecha_finalizacion']):false;
$idUsuario = $_SESSION['arreglo_datos_usuario']['id'];
//var_dump( $_SESSION['arreglo_datos_usuario']);exit;
//die($anio_lectivo."&&".$evento."&&".$fecha_inicio."&&".$fecha_final);

$arr_resultados = array();
$arr_param = [];
$cal = new CalendarioAcademico();

if ($anio_lectivo && $evento && $fecha_inicio && $fecha_final) {
    if ($id) {
       $arr_param['id'] = $id;
    }
    $arr_param['anio_lectivo'] = $anio_lectivo;
    $arr_param['fecha_inicio'] = $fecha_inicio;
    $arr_param['fecha_final'] = $fecha_final;
    $arr_param['idTipificacion'] = $evento;
    $arr_param['idUsuario'] = $idUsuario;
    //var_dump($arr_param);exit;
    if ($cal->save($arr_param)) {
            $arr_resultados['codigo'] = 200;
            $arr_resultados['class'] = "success";
            $arr_resultados['mensaje'] = "ok";
    } else {
            $arr_resultados['codigo'] = 500;
            $arr_resultados['class'] = "danger";
            $arr_resultados['mensaje'] = "Hubo un Error en la creación del Evento. ";
    }

} else {
            $arr_resultados['codigo'] = 500;
            $arr_resultados['class'] = "danger";
            $arr_resultados['mensaje'] = "No ha completado los campos obligatorios.";
}

/*
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
*/


echo json_encode($arr_resultados);


?>
