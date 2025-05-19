<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
require_once "InscripcionRendirMaterias.php";
require_once "AlumnoRindeMateria.php";

function removeItem($item,$arr) {
    $arr_resultado = [];
    foreach($arr as $value) {
        if (!($item[0]==$value[0] && $item[1]==$value[1])) {
            $arr_resultado[] = $value;
        }
    }

	return $arr_resultado;
}


// ******************************** MAIN **************************************************

$idAlumno = $_SESSION['idAlumno'];
$idMateria = $_POST['materia_id'];
$inscribir = $_POST['inscribir'];

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

if ($inscribir=='No') {

    if (!in_array([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_actualizadas'][] = [$idMateria,'No'];
    }
    $_SESSION['arr_materias_inscriptas_actualizadas'] = removeItem([$idMateria,'REGULAR'],$_SESSION['arr_materias_inscriptas_actualizadas']);
    $_SESSION['arr_materias_inscriptas_actualizadas'] = removeItem([$idMateria,'LIBRE'],$_SESSION['arr_materias_inscriptas_actualizadas']);
    $arr_resultado['res'] = 'No';
} else {
   
    if (!in_array([$idMateria,$inscribir],$_SESSION['arr_materias_inscriptas_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_actualizadas'][] = [$idMateria,$inscribir];
    }
    $_SESSION['arr_materias_inscriptas_actualizadas'] = removeItem([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_actualizadas']);
    $arr_resultado['res'] = 'Si';
}

echo json_encode($arr_resultado);

?>
