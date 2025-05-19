<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'AlumnoCursaMateria.php';
require_once 'Tipificacion.php';
require_once 'Constantes.php';

$idMateria = SanitizeVars::INT($_POST['materia_id']);
$idAlumno = SanitizeVars::INT($_POST['alumno_id']);
$nota = SanitizeVars::INT($_POST['nota']);
$anio_cursado = SanitizeVars::INT($_POST['anio_cursado']);
$idUsuario = $_SESSION['arreglo_datos_usuario']['id'];

$array_resultados = array();

$ahora = date("Y-m-d H:i:s");
$anioCursado = date('Y');

if ($idAlumno && $idMateria && $nota && $anio_cursado) {
    $fechaVencimientoRegularidad = ($anio_cursado+2).'-04-01';
    $objACM = new AlumnoCursaMateria();
    $objTipificacion = new Tipificacion();
    
    $idEstado = $objTipificacion->getTipificacionByCodigo(Constantes::CODIGO_ESTADO_REGULARIZO);
    $idCursado = $objTipificacion->getTipificacionByCodigo(Constantes::CODIGO_CURSADO_PRESENCIAL);

    $res = $objACM->save(['alumno_id'=>$idAlumno,
                          'materia_id'=>$idMateria,
                          'cursado_id'=>$idCursado['id'],
                          'estado_id'=>$idEstado['id'],
                          'nota'=>$nota,
                          'estado_final'=>'Regularizo',
                          'fecha_modificacion_nota'=>$ahora,
                          'anio_cursado'=>$anio_cursado,
                          'fecha_vencimiento_regularidad'=>$fechaVencimientoRegularidad,
                          'usuario_id'=>$idUsuario]);
    
    if ($res)  {         
      $array_resultados['codigo'] = 200;    
      $array_resultados['class'] = 'success';       
      $array_resultados['mensaje'] = "La Homologación se ha realizado.";
    } else {
      $array_resultados['codigo'] = 500;    
      $array_resultados['class'] = 'danger';       
      $array_resultados['mensaje'] = "La Homologación NO se ha realizado.";
    }
} else {
    $array_resultados['codigo'] = 500;    
    $array_resultados['class'] = 'danger';       
    $array_resultados['mensaje'] = "Faltan Datos Obligatarios.";
}
echo json_encode($array_resultados);

?>
