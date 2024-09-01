<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$idCarrera = SanitizeVars::INT($_POST['carrera_id']);
$anio = SanitizeVars::INT($_POST['anio']);

$array_resultados = array();
if ($idCarrera && $anio) {
    $sql = "SELECT a.*, p.email as email, p.telefono_caracteristica, p.telefono_numero, l.nombre as localidad, prov.nombre as provincia
            FROM alumno_estudia_carrera aec, alumno a, persona p, localidad l, provincia prov
            WHERE aec.idCarrera = $idCarrera AND
                  aec.anio = '$anio' AND
                  aec.idAlumno = a.id AND
                  a.dni = p.dni AND
                  p.idLocalidad = l.id AND
                  l.provincia_id = prov.id
            ORDER BY apellido asc, nombre asc";
    $resultado = mysqli_query($conex,$sql);
    

    if (mysqli_num_rows($resultado)>0) {
        
      $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
      $array_resultados['codigo'] = 100;
      $array_resultados['datos'] = $filas;
      
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['datos'] = "No existe la Carrera.";
    }
} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['datos'] = "Faltan Datos Obligatarios.";
}
//var_dump($array_resultados);
//var_dump(json_encode($array_resultados,JSON_UNESCAPED_UNICODE));
//die;
try{
//The exception is thrown.
echo json_encode($array_resultados,JSON_THROW_ON_ERROR );
// and in the catch block it's caught successfully:
}catch(Exception $e){
    echo $e->getMessage(); //This prints the message correctly.
    //$output = json_encode(array('msg'=>$e->getMessage()));
    //echo $output; //But this fails...displays {"msg":null}
}


?>
