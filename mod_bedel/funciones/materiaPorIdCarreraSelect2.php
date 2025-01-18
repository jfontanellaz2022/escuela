<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";


$idCarrera = SanitizeVars::INT($_REQUEST['carrera_id']);
$search = (isset($_GET['searchTerm']))?$_GET['searchTerm']:false;
$array_resultados = array();
$json = [];
if($search) {
        
  $sql = "SELECT m.*
          FROM carrera_tiene_materia ctm, materia m 
          WHERE ctm.idCarrera = $idCarrera AND
                ctm.idMateria = m.id AND 
                (m.nombre like '%$search%' OR m.id like '%$search%')
          ORDER BY anio asc, nombre asc";

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
