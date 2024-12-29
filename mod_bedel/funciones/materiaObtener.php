<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$array_resultados = array();
$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$json = [];
if($search) {
        $sql = "SELECT m.id, m.nombre, c.descripcion
                FROM materia m, carrera c, carrera_tiene_materia ctm
                WHERE (m.id=ctm.idMateria and ctm.idCarrera=c.id) and (m.nombre like '%$search%')";       
        //die($sql);         
        $resultado = mysqli_query($conex,$sql);
        if (mysqli_num_rows($resultado)>0) {
                while($row = mysqli_fetch_assoc($resultado)){
                        $json[] = ['id'=>$row['id'], 'text'=>$row['nombre'] .' ('.$row['id'] . ') - ' . $row['descripcion']];
                }
        };
} else {
        $json = [];   
}
echo json_encode($json);

?>
