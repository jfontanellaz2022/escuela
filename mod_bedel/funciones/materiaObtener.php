<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'Materia.php';

$array_resultados = array();
$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$json = [];
$obj = new Materia();
if($search) {
        
        $arr_res = $obj->getMateriaByName($search);
        foreach($arr_res as $fila) {
                $json[] = ['id'=>$fila['id'], 'text'=>$fila['nombre'] .' ('.$fila['id'] . ') - ' . $fila['descripcion']];
        }
       
} else {
        $json = [];   
}
echo json_encode($json);

?>
