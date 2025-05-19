<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once 'SanitizeCustom.class.php';
require_once 'CalendarioAcademico.php';
//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
$array_resultados = [];
if ($token!=$_SESSION['token']) {
  $array_resultados['codigo'] = 500;
  $array_resultados['class'] = 'danger';
  $array_resultados['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($array_resultados);die;
}
//****************************************************** */

$objeto = new CalendarioAcademico;
$arr_evento_final = [];
for ($i=1011;$i<=1012;$i++) {
   $arr_tmp = [];   
   $arr_evento = $objeto->getEventoActivoByCodigo($i);
   if (count($arr_evento)>0) {
        $arr_tmp['id'] = $arr_evento[0]['idEvento'];
        $arr_tmp['codigo'] = $i;
        $arr_tmp['descripcion'] = $arr_evento[0]['evento_descripcion'];
        $arr_tmp['idCalendario'] = $arr_evento[0]['id'];
        $arr_tmp['fecha_inicio'] = $arr_evento[0]['fecha_inicio'];
        $arr_tmp['fecha_final'] = $arr_evento[0]['fecha_final'];
        $arr_evento_final[] = $arr_tmp;
   }
}

$array_resultados['codigo'] = 200;
$array_resultados['mensaje'] = "ok";
$array_resultados['datos'] = $arr_evento_final;

echo json_encode($array_resultados);

?>
