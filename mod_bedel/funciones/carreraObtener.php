<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'Carrera.php';

$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$json = [];
$objeto = new Carrera();
if($search) {
      
      $arr_datos_carrera = $objeto->getCarreraByName($search);
      foreach ($arr_datos_carrera as $fila) {
            $json[] = ['id'=>$fila['id'], 'text'=>$fila['descripcion'].' ('.$fila['codigo'].')'];
        }
} else {
      $json = [];   
}

echo json_encode($json);


?>
