<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/controllers/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
require_once 'SanitizeCustom.class.php';
require_once 'ProfesorController.php';


//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 500;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}
//****************************************************** */


function getAllCarreras() {
      $objeto = new ProfesorController;
      $arr_carreras = $objeto->getCarreras();
      $arr_res = [];
      
      if (is_array($arr_carreras)) {
            $arr_res['codigo'] = 200;
            $arr_res['mensaje'] = "ok";
            $arr_res['datos'] = $arr_carreras;
      } else {
            $arr_res['codigo'] = 500;
            $arr_res['mensaje'] = "Error 500: Hubo un error en la consulta.";
            $arr_res['datos'] = [];
      }

      return $arr_res;
}

function getAllCarrerasByProfesor($profesor_id) {
      $arr_res = [];
      if ($profesor_id) {
            $objeto = new ProfesorController;
            $arr_carreras = $objeto->getAllCarrerasByProfesor($profesor_id);
            if (is_array($arr_carreras)) {
               $array_res['codigo'] = 200;
               $array_res['mensaje'] = "ok";
               $array_res['datos'] = $arr_carreras;
            } else {
               $array_res['codigo'] = 500;
               $array_res['mensaje'] = "Error 500: Hubo un error en la consulta.";
               $array_res['datos'] = [];
            }
         } else {
            $array_res['codigo'] = 400;
            $array_res['mensaje'] = "Error 400: No ingreso el Profesor.";
            $array_res['datos'] = [];
         }

      return $arr_res;
}


function getMateriasByIdCarrera($carrera_id) {
      $array_res = [];
      if ($carrera_id) {
            $ctm = new CarreraTieneMateria;
            $arr_materias_por_carrera = $ctm->getMateriasByIdCarreraDetalle($carrera_id);
         
            $array_res['codigo'] = 200;
            $array_res['mensaje'] = "ok";
            $array_res['datos'] = $arr_materias_por_carrera;
         } else {
            $array_res['codigo'] = 400;
            $array_res['mensaje'] = "Error 400: No ingreso el ID de Carrera.";
            $array_res['datos'] = [];
         }
      return $array_res;
}



$array_resultados = [];

if ($opcion = 'CARRERAS') {
      $array_resultados = getAllCarreras();
} else if ($opcion = 'CARRERAS_POR_PROFESOR') {
      $array_resultados = getAllCarrerasByProfesor($profesor_id);
} else if ($opcion = 'MATERIAS_POR_CARRERA') {
      $array_resultados = getMateriasByIdCarrera($carrera_id);
}




echo json_encode($array_resultados);







?>
