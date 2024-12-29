<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
require_once "_seguridad.php";

$array_resultados = [];
$anio_actual = date('Y');
$sql = "SELECT a.id, a.AnioLectivo, a.fechaInicioEvento, a.fechaFinalEvento, e.codigo, e.descripcion 
        FROM calendarioacademico a, evento e 
        WHERE a.AnioLectivo = '$anio_actual' and a.idEvento = e.id and 
                      (e.codigo = 1005 or e.codigo = 1006 or e.codigo = 1007 or e.codigo = 1008) 
        ORDER BY a.id DESC LIMIT 1";       
       //die($sql);         
$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
        $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
        $array_resultados['codigo'] = 100;
        //die($filas[0]['codigo']); 
        if ($filas[0]['codigo']=='1005' || $filas[0]['codigo']=='1007') { 
                $filas[0]['llamados'] = 2; 
        } else {
                $filas[0]['llamados'] = 1;       
        };
        $array_resultados['datos'] = $filas;
} else {
        $array_resultados['codigo'] = 11;
        $array_resultados['datos'] = "No existe Turno.";   
};

echo json_encode($array_resultados);

?>
