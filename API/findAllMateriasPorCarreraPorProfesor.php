<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'seguridadNivel2.php';
require_once 'SanitizeCustom.class.php';
require_once 'Carrera.php';
require_once 'Profesor.php';

$id_profesor = (isset($_POST['profesor']))?SanitizeCustom::INT($_POST['profesor']):false;
$id_carrera = (isset($_POST['carrera']))?SanitizeCustom::INT($_POST['carrera']):false;

$array_resultados = [];

if ($id_profesor) {
   
   $carrera = new Carrera;
   $arr_materias_carrera = $carrera->getMateriasPorIdCarrera($id_carrera);
   
   $profesor = new Profesor();
   $arr_materias_dicta = $profesor->getMateriasByProfesor($id_profesor);

   //var_dump($arr_materias_dicta);exit;
   
   $arr_materias = [];

   foreach ($arr_materias_dicta as $materia_item) {
      foreach ($arr_materias_carrera as $mat_carrera_item) {
         if ($materia_item['materia_id']==$mat_carrera_item['materia_id']) {
            $arr_materias[] = $materia_item;
         };   
      }
   }

   $array_resultados['codigo'] = 200;
   $array_resultados['mensaje'] = "ok";
   $array_resultados['datos'] = $arr_materias;
} else {
   $array_resultados['codigo'] = 400;
   $array_resultados['mensaje'] = "Error 400: No ingreso el Profesor.";
   $array_resultados['datos'] = [];
}
//var_dump($array_resultados);exit;
echo json_encode($array_resultados);







?>
