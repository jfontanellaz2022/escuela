<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN CURSANDO UNA MATERIA EN UNA AÑO DADO             **
//***************************************************************************************************

//***************************************************************************************************
//** Parámetros                                                                                    **
//** ______________________________________________________________________________________________**
//** id_materia                                                                                    **
//** sinLibres                                                                                     **
//** anio                                                                                          **
//** ______________________________________________________________________________________________**
//** Valores Devueltos                                                                             **
//** ______________________________________________________________________________________________**
//***************************************************************************************************


set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
require_once('Alumno.php');
require_once 'SanitizeCustom.class.php';

$id_materia = (isset($_POST['materia']))?SanitizeCustom::INT($_POST['materia']):false;
$sinLibres = (isset($_POST['sinLibres']) && (in_array($_POST['sinLibres'],[true,false])))?$_POST['sinLibres']:false;
$anio = (isset($_POST['anio']))?SanitizeCustom::INT($_POST['anio']):date('Y');

$token = (isset($_GET['token']))?$_GET['token']:false;

if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 500;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}

function sacarAlumnosPorMateriaPorAnio($arr_alumnos,$anio) {
   $arr_alumnos_por_anio = [];
   foreach ($arr_alumnos as $item) {
       if ($item['anio_cursado']==$anio) {
         $arr_alumnos_por_anio[] = $item;
       };
   }
   //var_dump($anio,$arr_alumnos_por_anio);
   return $arr_alumnos_por_anio;
}


function sacarAlumnosPorMateria($arr_alumnos,$sinLibres) {
   echo "ver si se usa esto";
}

if ($id_materia) {
   $objeto = new Alumno;
   //var_dump($objeto->getAllAlumnosByMateria($id_materia));exit;
   $arr_alumnos_por_anio = sacarAlumnosPorMateriaPorAnio($objeto->getAllAlumnosByMateria($id_materia),$anio);

   

   if (is_array($arr_alumnos_por_anio)) {
      $array_resultados['codigo'] = 200;
      $array_resultados['mensaje'] = "ok";
      $array_resultados['datos'] = $arr_alumnos_por_anio;
   } else {
      $array_resultados['codigo'] = 500;
      $array_resultados['mensaje'] = "Error 500: Hubo un error en la consulta.";
      $array_resultados['datos'] = [];
   }
} else {
   $array_resultados['codigo'] = 400;
   $array_resultados['mensaje'] = "Error 400: No ingreso la Materia.";
   $array_resultados['datos'] = [];
}


echo json_encode($array_resultados);



?>
