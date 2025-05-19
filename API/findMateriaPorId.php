<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS MATERIAS        **
//***************************************************************************************************
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once 'SanitizeCustom.class.php';

require_once 'Materia.php';

$id_materia = (isset($_POST['materia']))?SanitizeCustom::INT($_POST['materia']):false;

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
