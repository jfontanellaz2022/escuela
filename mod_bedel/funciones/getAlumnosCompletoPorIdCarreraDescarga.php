<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
//require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once "Carrera.php";
require_once "Alumno.php";

$idCarrera = SanitizeVars::INT($_REQUEST['carrera_id']);
$anio = SanitizeVars::INT($_REQUEST['anio']);


$param['carrera_id'] = $idCarrera;
$param['anio'] = $anio;

$objCarrera = new Carrera();
$carrera_nombre = $objCarrera->getCarreraById($idCarrera)['descripcion'];


$obj = new Alumno();
$arr_datos = $obj->getAllAlumnosByCarrera($param);



$arr_datos_formateados = [];
if ((!empty($arr_datos))) {
    $fileName = "ListadoInscriptos.xls";

    foreach ($arr_datos as $item) {
        $arr_tmp = [];
        $arr_tmp['Apellido y Nombre'] = utf8_decode($item['apellido']) .', '. utf8_decode($item['nombre']);
        $arr_tmp['DNI'] = $item['dni'];
        $arr_tmp['Email'] = $item['email'];
        $arr_tmp['Telefono'] = '(' . $item['telefono_caracteristica'] . ') ' . $item['telefono_numero'];
        $arr_tmp['Localidad'] = $item['localidad_nombre'] . '-' . $item['provincia_nombre'];
        $arr_datos_formateados[] = $arr_tmp;
    }
    
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename= $fileName");

    $mostrar_columnas = false;
    echo "Listado de Inscriptos $carrera_nombre" . "\n";
    foreach($arr_datos_formateados as $libro) {
        if(!$mostrar_columnas) {
        echo implode("\t", array_keys($libro)) . "\n";
        $mostrar_columnas = true;
        }
        echo implode("\t", array_values($libro)) . "\n";
    }
       
} else {

    echo 'No hay datos a exportar';

}

exit;

?>
