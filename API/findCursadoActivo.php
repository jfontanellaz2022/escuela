<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'seguridadNivel2.php';
require_once 'SanitizeCustom.class.php';
require_once 'CalendarioAcademico.php';

$objeto = new CalendarioAcademico;
$arr_evento_final = [];
for ($i=1014;$i<=1016;$i++) {
   $arr_tmp = [];   
   $arr_evento = $objeto->getEventoActivoByCodigo($i);
   if (count($arr_evento)>0) {
        $arr_tmp['id'] = $arr_evento[0]['idEvento'];
        $arr_tmp['codigo'] = $i;
        $arr_tmp['descripcion'] = $arr_evento[0]['evento_descripcion'];
        $arr_tmp['idCalendario'] = $arr_evento[0]['id'];
        $arr_tmp['fechaInicioEvento'] = $arr_evento[0]['fechaInicioEvento'];
        $arr_tmp['fechaFinalEvento'] = $arr_evento[0]['fechaFinalEvento'];
        $arr_evento_final[] = $arr_tmp;
   }
}



$array_resultados['codigo'] = 200;
$array_resultados['mensaje'] = "ok";
$array_resultados['datos'] = $arr_evento_final;

echo json_encode($array_resultados);

?>
