<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once "InscripcionRendirMaterias.php";
require_once "AlumnoRindeMateria.php";
require_once "Constantes.php";

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
    if (!in_array([$idMateria,Constantes::CODIGO_CURSADO_PRESENCIAL],$_SESSION['arr_materias_inscriptas_cursar_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_cursar_actualizadas'][] = [$idMateria,Constantes::CODIGO_CURSADO_PRESENCIAL];
    }
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,Constantes::CODIGO_CURSADO_SEMIPRESENCIAL],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,Constantes::CODIGO_CURSADO_LIBRE],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
} else if ($inscribir=='Semipresencial') {
    if (!in_array([$idMateria,Constantes::CODIGO_CURSADO_SEMIPRESENCIAL],$_SESSION['arr_materias_inscriptas_cursar_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_cursar_actualizadas'][] = [$idMateria,Constantes::CODIGO_CURSADO_SEMIPRESENCIAL];
    }
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,Constantes::CODIGO_CURSADO_PRESENCIAL],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,Constantes::CODIGO_CURSADO_LIBRE],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
} else if ($inscribir=='Libre') {
    if (!in_array([$idMateria,Constantes::CODIGO_CURSADO_LIBRE],$_SESSION['arr_materias_inscriptas_cursar_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_cursar_actualizadas'][] = [$idMateria,Constantes::CODIGO_CURSADO_LIBRE];
    }
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,Constantes::CODIGO_CURSADO_PRESENCIAL],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,Constantes::CODIGO_CURSADO_SEMIPRESENCIAL],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
} else if ($inscribir=='No') {
    if (!in_array([$idMateria,'No'],$_SESSION['arr_materias_inscriptas_cursar_actualizadas'])) {
        $_SESSION['arr_materias_inscriptas_cursar_actualizadas'][] = [$idMateria,'No'];
    }
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,Constantes::CODIGO_CURSADO_PRESENCIAL],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,Constantes::CODIGO_CURSADO_SEMIPRESENCIAL],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
    $_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = removeItem([$idMateria,Constantes::CODIGO_CURSADO_LIBRE],$_SESSION['arr_materias_inscriptas_cursar_actualizadas']);
}
//var_dump($_SESSION['arr_materias_inscriptas_cursar_actualizadas']);die;
$arr_resultado['res'] = $inscribir;

echo json_encode($arr_resultado);

?>
