<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib');
require_once 'seguridadNivel2.php';
require_once 'SanitizeCustom.class.php';
require_once 'CarreraTieneMateria.php';

$id_carrera = (isset($_POST['carrera']))?SanitizeCustom::INT($_POST['carrera']):false;

$array_resultados = [];
if ($id_carrera) {
   $ctm = new CarreraTieneMateria;
   $arr_materias_por_carrera = $ctm->getMateriasByIdCarreraDetalle($id_carrera);

   $array_resultados['codigo'] = 200;
   $array_resultados['mensaje'] = "ok";
   $array_resultados['datos'] = $arr_materias_por_carrera;
} else {
   $array_resultados['codigo'] = 400;
   $array_resultados['mensaje'] = "Error 400: No ingreso el ID de Carrera.";
   $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);

?>
