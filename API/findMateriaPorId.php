<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS MATERIAS        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'seguridadNivel2.php';
require_once 'SanitizeCustom.class.php';

require_once 'Materia.php';

$id_materia = (isset($_POST['materia']))?SanitizeCustom::INT($_POST['materia']):false;

$array_resultados = [];

if ($id_materia) {
    $m = new Materia();
    $arr_datos_materia = $m->getMateriaById($id_materia);

    $array_resultados['codigo'] = 200;
    $array_resultados['mensaje'] = "ok";
    $array_resultados['datos'] = $arr_datos_materia;

} else {
    $array_resultados['codigo'] = 400;
    $array_resultados['mensaje'] = "Error 400: No ingreso Materia.";
    $array_resultados['datos'] = [];
}
echo json_encode($array_resultados);

?>
