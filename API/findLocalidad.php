<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib');
require_once 'Localidad.php';
require_once 'SanitizeCustom.class.php';

//$nombre = (isset($_POST['nombre']))?SanitizeCustom::STRING($_POST['nombre']):false;
$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$json = [];
$objeto = new Localidad();
if($search) {
      $arr_datos_lodalidad = $objeto->getByName($search);
      //var_dump($arr_datos_lodalidad);exit;

      foreach ($arr_datos_lodalidad as $fila) {
            $json[] = ['id'=>$fila['id'], 'text'=>$fila['localidad_nombre'] . ' (PCIA. ' . strtoupper($fila['provincia_nombre']) . ')'];
      }
} else {
      $json = [];   
}

echo json_encode($json);



?>
