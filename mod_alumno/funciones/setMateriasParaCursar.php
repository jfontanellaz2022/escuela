<?php
session_start();
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../lib');
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

if ($inscribir=='Presencial') {
    if (!in_array([$idMateria,'Presencial'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_cursar_actualizadas'][] = [$idMateria,'Presencial'];
    }
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'Semipresencial'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'Libre'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
} else if ($inscribir=='Semipresencial') {
    if (!in_array([$idMateria,'Semipresencial'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_cursar_actualizadas'][] = [$idMateria,'Semipresencial'];
    }
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'Presencial'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'Libre'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
} else if ($inscribir=='Libre') {
    if (!in_array([$idMateria,'Libre'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_cursar_actualizadas'][] = [$idMateria,'Libre'];
    }
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'Presencial'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'Semipresencial'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
} else if ($inscribir=='No') {
    if (!in_array([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_cursar_actualizadas'][] = [$idMateria,'No'];
    }
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'Presencial'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'Semipresencial'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'Libre'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
}
//var_dump($_SESSION['arr_materias_inscriptas_cursar_actualizadas']);die;
$arr_resultado['res'] = $inscribir;

echo json_encode($arr_resultado);

?>
