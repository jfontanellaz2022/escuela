<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN INSCRIPTOS UNA CARRERA PORR ID DE CARRERA        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once 'Profesor.php';
require_once 'ProfesorPerteneceCarrera.php';
require_once 'ProfesorDictaMateria.php';
require_once 'SanitizeCustom.class.php';

$id_profesor = (isset($_POST['profesor']))?SanitizeCustom::INT($_POST['profesor']):false;
$id_carrera = (isset($_POST['carrera']))?SanitizeCustom::INT($_POST['carrera']):false;

//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
$array_resultados = [];
if ($token!=$_SESSION['token']) {
  $array_resultados['codigo'] = 500;
  $array_resultados['class'] = 'danger';
  $array_resultados['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($array_resultados);die;
}
//****************************************************** */

if ($id_profesor && $id_carrera) {
      $objetoProfesor = new Profesor();
      $objetoPPC = new ProfesorPerteneceCarrera();

      $arr_materias_en_la_carrera = $objetoProfesor->getAllMateriasByProfesorByCarrera($id_profesor,$id_carrera);

      if (is_array($arr_materias_en_la_carrera) && count($arr_materias_en_la_carrera)>0) {
            $objPDM = new ProfesorDictaMateria();
            foreach ($arr_materias_en_la_carrera as $item) {
                  $objPDM->deleteByProfesorByMateria($id_profesor,$item['materia_id']);
            };

      }      

      $objetoPPC->deleteProfesorPerteneceCarreraByProfesorByCarrera($id_profesor,$id_carrera); // acaaaa

      $array_resultados['codigo'] = 200;
      $array_resultados['class'] = "success";
      $array_resultados['mensaje'] = "ok";
      $array_resultados['datos'] = [];
} else {
      $array_resultados['codigo'] = 400;
      $array_resultados['class'] = "danger";
      $array_resultados['mensaje'] = "Error 400: Datos Ingresados no son correctos.";
      $array_resultados['datos'] = [];
}

echo json_encode($array_resultados);







?>
