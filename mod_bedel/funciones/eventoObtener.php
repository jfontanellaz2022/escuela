<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once "Tipificacion.php";

$array_resultados = array();
$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$json = [];
$obj = new Tipificacion();

if($search) {
        $arr_res = $obj->getAllEventos($search);
        if (!empty($arr_res)) {
                foreach ($arr_res as $fila) {
                    $json[] = ['id'=>$fila['id'], 'text'=>$fila['nombre'].' ('.$fila['codigo'].')'];
                };
        }
} else {
        $json = [];   
}
echo json_encode($json);

?>
