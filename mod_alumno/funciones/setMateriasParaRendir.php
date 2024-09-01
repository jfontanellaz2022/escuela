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

if ($inscribir=='Si') {
    if (!in_array([$idMateria,'Si'],$_SESSION['arr_materias_inscriptas_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_actualizadas'][] = [$idMateria,'Si'];
    }
    $_SESSION['arr_materias_inscriptas_actualizadas'] = removeItem([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_actualizadas']);
} else if ($inscribir=='No') {
    if (!in_array([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_actualizadas'][] = [$idMateria,'No'];
    }
    $_SESSION['arr_materias_inscriptas_actualizadas'] = removeItem([$idMateria,'Si'],$_SESSION['arr_materias_inscriptas_actualizadas']);
}

$arr_resultado['res'] = $inscribir;
echo json_encode($arr_resultado);

?>
