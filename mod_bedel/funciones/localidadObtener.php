<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$array_resultados = array();
$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$json = [];
if($search) {
        
        $sql = "SELECT l.id, l.nombre, p.nombre as provincia_nombre
                FROM localidad l, provincia p
                WHERE l.provincia_id=p.id and (l.nombre like '%$search%')";
        $resultado = mysqli_query($conex,$sql);
        if (mysqli_num_rows($resultado)>0) {
                while($row = mysqli_fetch_assoc($resultado)){
                        $json[] = ['id'=>$row['id'], 'text'=>$row['nombre'].' (PCIA. '.strtoupper($row['provincia_nombre']).')'];
                }
        };
} else {
        $json = [];   
}
echo json_encode($json);

?>
