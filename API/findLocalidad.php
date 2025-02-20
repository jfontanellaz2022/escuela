<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib');
session_start();
require_once 'Localidad.php';
require_once 'SanitizeCustom.class.php';

$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$token = (isset($_GET['token']))?$_GET['token']:false;
$json = [];
if ($token==$_SESSION['token']) {
      $objeto = new Localidad();
      if($search) {
            $arr_datos_lodalidad = $objeto->getByName($search);
            foreach ($arr_datos_lodalidad as $fila) {
                  $json[] = ['id'=>$fila['id'], 'text'=>$fila['localidad_nombre'] . ' (PCIA. ' . strtoupper($fila['provincia_nombre']) . ')'];
            }
      } else {
            $json = [];   
      }
} else {
      $json = [];
}
echo json_encode($json);



?>
