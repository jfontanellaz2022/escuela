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

$opcion = $_POST['opcion'];

function getAllCarreras() {
      $objeto = new ProfesorController;
      $arr_carreras = $objeto->getCarreras();
      $arr_res = [];
      
      if (is_array($arr_carreras)) {
            $arr_res['codigo'] = 200;
            $array_res['class'] = 'success';
            $arr_res['mensaje'] = "ok";
            $arr_res['datos'] = $arr_carreras;
      } else {
            $arr_res['codigo'] = 500;
            $array_res['class'] = 'danger';
            $arr_res['mensaje'] = "Error 500: Hubo un error en la consulta.";
            $arr_res['datos'] = [];
      }

      return $arr_res;
}

function getAllMaterias() {
      $objeto = new ProfesorController;
      $arr_materias = $objeto->getMaterias();
      $arr_res = [];
      
      if (is_array($arr_materias)) {
            $arr_res['codigo'] = 200;
            $array_res['class'] = 'success';
            $arr_res['mensaje'] = "ok";
            $arr_res['datos'] = $arr_materias;
      } else {
            $arr_res['codigo'] = 500;
            $array_res['class'] = 'danger';
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
            
            if (!empty($arr_carreras)) {
               $arr_res['codigo'] = 200;
               $array_res['class'] = 'success';
               $arr_res['mensaje'] = "ok";
               $arr_res['datos'] = $arr_carreras;
            } else {
               $arr_res['codigo'] = 500;
               $array_res['class'] = 'danger';
               $arr_res['mensaje'] = "Error 500: Hubo un error en la consulta.";
               $arr_res['datos'] = [];
            }
         } else {
            $arr_res['codigo'] = 400;
            $array_res['class'] = 'danger';
            $arr_res['mensaje'] = "Error 400: No ingreso el Profesor.";
            $arr_res['datos'] = [];
         }

      return $arr_res;
}


function getMateriasByCarrera($carrera_id) {
      $array_res = [];
      if ($carrera_id) {
            $objeto = new ProfesorController;
            $arr_materias_por_carrera = $objeto->getMateriasByCarrera($carrera_id);
         
            $array_res['codigo'] = 200;
            $array_res['class'] = 'success';
            $array_res['mensaje'] = "ok";
            $array_res['datos'] = $arr_materias_por_carrera;
         } else {
            $array_res['codigo'] = 400;
            $array_res['class'] = 'danger';
            $array_res['mensaje'] = "Error 400: No ingreso el ID de Carrera.";
            $array_res['datos'] = [];
         }
      return $array_res;
}



function getAllMateriasByCarreraByProfesor($param) {
      $objeto = new ProfesorController;
      $arr_materias = $objeto->getMateriasByCarreraByProfesor($param);      
      $array_res = [];
      //var_dump($arr_materias);exit;

      if (!empty($arr_materias)) {   
      $array_res['codigo'] = 200;
      $array_res['class'] = 'success';
      $array_res['mensaje'] = "ok";
      $array_res['datos'] = $arr_materias;
      } else {
      $array_res['codigo'] = 400;
      $array_res['class'] = 'danger';
      $array_res['mensaje'] = "Error 400: No ingreso el Profesor.";
      $array_res['datos'] = [];
      }
      
      return $array_res;
}


function getAllAlumnosByMateria($param) {
      $objeto = new ProfesorController;
      //var_dump($param);exit;
      $arr_alumnos = $objeto->getAllAlumnosByMateria($param);      
      $array_res = [];
      //var_dump($arr_alumnos);exit;

      if (!empty($arr_alumnos)) {   
      $array_res['codigo'] = 200;
      $array_res['class'] = 'success';
      $array_res['mensaje'] = "ok";
      $array_res['datos'] = $arr_alumnos;
      } else {
      $array_res['codigo'] = 400;
      $array_res['class'] = 'danger';
      $array_res['mensaje'] = "Error 400: No ingreso el Profesor.";
      $array_res['datos'] = [];
      }
      return $array_res;
}

function setProfesorMateria($param) {
      $array_res = [];
      $obj = new ProfesorController;
      if ( isset($param['profesor_id']) && $param['profesor_id']!=NULL && 
	     isset($param['materia_id']) && $param['materia_id']!=NULL ) {
            $res = $obj->setProfesorMateria($param);
            if ($res) {
                  $array_res['codigo'] = 200;
                  $array_res['class'] = 'success';
                  $array_res['mensaje'] = "Se Vinculo el Profesor a la Materia.";
                  $array_res['datos'] = [];
            } else {
                  $array_res['codigo'] = 400;
                  $array_res['class'] = 'danger';
                  $array_res['mensaje'] = "No se Vinculo el Profesor a la Materia.";
                  $array_res['datos'] = [];   
            }
      }  else {
            $array_res['codigo'] = 400;
            $array_res['class'] = 'danger';
            $array_res['mensaje'] = "No se Vinculo el Profesor a la Materia.";
            $array_res['datos'] = [];
      }
      return $array_res;
}


function setProfesorCarrera($param) {
      $array_res = [];
      $obj = new ProfesorController;
      if ( isset($param['profesor_id']) && $param['profesor_id']!=NULL && 
	     isset($param['carrera_id']) && $param['carrera_id']!=NULL ) {
            $res = $obj->setProfesorCarrera($param);
            if ($res) {
                  $array_res['codigo'] = 200;
                  $array_res['class'] = 'success';
                  $array_res['mensaje'] = "Se Vinculo el Profesor a la Carrera.";
                  $array_res['datos'] = [];
            } else {
                  $array_res['codigo'] = 400;
                  $array_res['class'] = 'danger';
                  $array_res['mensaje'] = "No se Vinculo el Profesor a la Carrera.";
                  $array_res['datos'] = [];   
            }
      }  else {
            $array_res['codigo'] = 400;
            $array_res['class'] = 'danger';
            $array_res['mensaje'] = "No se Vinculo el Profesor a la Carrera.";
            $array_res['datos'] = [];
      }
      return $array_res;
}


function delProfesorMateria($param) {
      $array_res = [];
      $obj = new ProfesorController;
      if ( isset($param['profesor_id']) && $param['profesor_id']!=NULL && 
	     isset($param['materia_id']) && $param['materia_id']!=NULL ) {
            $res = $obj->deleteProfesorMateria($param);
            if ($res) {
                  $array_res['codigo'] = 200;
                  $array_res['class'] = 'success';
                  $array_res['mensaje'] = "Se Desvinculo el Profesor a la Materia.";
                  $array_res['datos'] = [];
            } else {
                  $array_res['codigo'] = 400;
                  $array_res['class'] = 'danger';
                  $array_res['mensaje'] = "No se Desvinculo el Profesor a la Materia.";
                  $array_res['datos'] = [];   
            }
      }  else {
            $array_res['codigo'] = 400;
            $array_res['class'] = 'danger';
            $array_res['mensaje'] = "No se Desvinculo el Profesor a la Materia.";
            $array_res['datos'] = [];
      }
      return $array_res;
}


function delProfesorCarrera($param) {
      $array_res = [];
      $obj = new ProfesorController;
      if ( isset($param['profesor_id']) && $param['profesor_id']!=NULL && 
	     isset($param['carrera_id']) && $param['carrera_id']!=NULL ) {
            $res = $obj->deleteProfesorCarrera($param);
            if ($res) {
                  $array_res['codigo'] = 200;
                  $array_res['class'] = 'success';
                  $array_res['mensaje'] = "Se Desvinculo el Profesor a la Carrera.";
                  $array_res['datos'] = [];
            } else {
                  $array_res['codigo'] = 400;
                  $array_res['class'] = 'danger';
                  $array_res['mensaje'] = "No se Desvinculo el Profesor a la Carrera.";
                  $array_res['datos'] = [];   
            }
      }  else {
            $array_res['codigo'] = 400;
            $array_res['class'] = 'danger';
            $array_res['mensaje'] = "No se Desvinculo el Profesor a la Carrera.";
            $array_res['datos'] = [];
      }
      return $array_res;
}



$array_resultados = [];

if ($opcion == 'CARRERAS') {
      $array_resultados = getAllCarreras();
} else if ($opcion == 'MATERIAS') {
      $array_resultados = getAllMaterias();
} else if ($opcion == 'CARRERAS_POR_PROFESOR') {
      $profesor_id = $_POST['profesor'];
      $array_resultados = getAllCarrerasByProfesor($profesor_id);
} else if ($opcion == 'MATERIAS_POR_CARRERA') {
      $carrera_id = $_POST['carrera_id'];
      $array_resultados = getMateriasByCarrera($carrera_id);
} else if ($opcion == 'MATERIAS_CARRERA_PROFESOR') {
      $carrera_id = $_POST['carrera_id'];
      $profesor_id = $_POST['profesor_id'];
      $turno_id = isset($_POST['turno_id'])?$_POST['turno_id']:NULL;
      $array_resultados = getAllMateriasByCarreraByProfesor(['carrera_id'=>$carrera_id,'profesor_id'=>$profesor_id,'turno_id'=>$turno_id]);
} else if ($opcion == 'MATERIA_ALUMNOS_CURSADO') {
      $materia_id = $_POST['materia_id'];
      $anio_cursado = isset($_POST['anio'])?$_POST['anio']:NULL;
      $cursado_id = isset($_POST['cursado_id'])?$_POST['cursado_id']:NULL;
      $array_resultados = getAllAlumnosByMateria(['materia_id'=>$materia_id,'anio'=>$anio_cursado,'cursado_id'=>$cursado_id]);
} else if ($opcion == 'VINCULAR_PROFESOR_MATERIA') {
      $materia_id = $_POST['materia_id'];
      $profesor_id = isset($_POST['profesor_id'])?$_POST['profesor_id']:NULL;
      $array_resultados = setProfesorMateria(['profesor_id'=>$profesor_id,'materia_id'=>$materia_id]);
} else if ($opcion == 'VINCULAR_PROFESOR_CARRERA') {
      $carrera_id = $_POST['carrera_id'];
      $profesor_id = isset($_POST['profesor_id'])?$_POST['profesor_id']:NULL;
      $array_resultados = setProfesorCarrera(['profesor_id'=>$profesor_id,'carrera_id'=>$carrera_id]);
} else if ($opcion == 'DESVINCULAR_PROFESOR_MATERIA') {
      $materia_id = $_POST['materia_id'];
      $profesor_id = isset($_POST['profesor_id'])?$_POST['profesor_id']:NULL;
      $array_resultados = delProfesorMateria(['profesor_id'=>$profesor_id,'materia_id'=>$materia_id]);
} else if ($opcion == 'DESVINCULAR_PROFESOR_CARRERA') {
      $carrera_id = $_POST['carrera_id'];
      $profesor_id = isset($_POST['profesor_id'])?$_POST['profesor_id']:NULL;
      $array_resultados = delProfesorCarrera(['profesor_id'=>$profesor_id,'carrera_id'=>$carrera_id]);
}


echo json_encode($array_resultados);





?>
