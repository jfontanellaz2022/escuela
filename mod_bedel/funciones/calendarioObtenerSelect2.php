<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$array_resultados = array();
$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$json = [];
if($search) {
        
        $sql = "SELECT c.id as id, e.codigo, e.descripcion as nombre
                FROM calendarioacademico c, evento e 
                WHERE c.idEvento = e.id and (e.codigo = 1000 or e.codigo = 1008 or e.codigo = 1005 or e.codigo = 1006 or e.codigo = 1007 or e.codigo = 1009 or e.codigo = 1010 or e.codigo = 1022) and 
                     (e.descripcion like '%$search%' or c.id like '%$search%')
                ORDER BY c.id DESC";

        $resultado = mysqli_query($conex,$sql);
        if (mysqli_num_rows($resultado)>0) {
                while($row = mysqli_fetch_assoc($resultado)){
                        $json[] = ['id'=>$row['id'], 'text'=>$row['nombre'].' ('.$row['id'].')'];
                }
        };
} else {
        $json = [];   
}
echo json_encode($json);

?>
