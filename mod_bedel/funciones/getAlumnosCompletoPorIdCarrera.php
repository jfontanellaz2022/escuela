<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once "Alumno.php";


$idCarrera = SanitizeVars::INT($_POST['carrera_id']);
$anio = SanitizeVars::INT($_POST['anio']);

$array_resultados = array();
if ($idCarrera && $anio) {
    $param['carrera_id'] = $idCarrera;
    $param['anio'] = $anio;
    $obj = new Alumno();
    $arr_datos = $obj->getAllAlumnosByCarrera($param);

    /*

    $sql = "SELECT a.*, p.email as email, p.telefono_caracteristica, p.telefono_numero, l.nombre as localidad, prov.nombre as provincia
            FROM alumno_estudia_carrera aec, alumno a, persona p, localidad l, provincia prov
            WHERE aec.idCarrera = $idCarrera AND
                  aec.anio = '$anio' AND
                  aec.idAlumno = a.id AND
                  a.dni = p.dni AND
                  p.idLocalidad = l.id AND
                  l.provincia_id = prov.id
            ORDER BY apellido asc, nombre asc";

    */

    
    $array_resultados['codigo'] = 200;
    $array_resultados['alert'] = "success";
    $array_resultados['mensaje'] = "OK";
    $array_resultados['datos'] = $arr_datos;

      
/*    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['datos'] = "No existe la Carrera.";
    }*/

} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['datos'] = "Faltan Datos Obligatarios.";
}
//var_dump($array_resultados);
//var_dump(json_encode($array_resultados,JSON_UNESCAPED_UNICODE));
//die;

//The exception is thrown.
echo json_encode($array_resultados);


?>
