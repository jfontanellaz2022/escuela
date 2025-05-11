<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once "InscripcionCursarMaterias.php";
require_once "AlumnoCursaMateria.php";
require_once "Materia.php";

$idAlumno = $_SESSION['idAlumno'];
$idCarrera = $_POST['carrera_id'];
$anio = date('Y');
$arr_resultado = [];
$estado = 1; //1 No esta Inscripta '', 2 Ya esta Inscripta 'Cursando'

$icm = new InscripcionCursarMaterias();
$arr_en_condiciones_de_cursar = $icm->getArregloMateriasVerificadasParaInscribirseDetalles($idAlumno,$idCarrera);
$acm = new AlumnoCursaMateria();
$arreglo_materias_inscriptas = $acm->getMateriasCursadasByEstadoByAnio($idAlumno,'Cursando',$anio);
function has_estado($materia_id,$arr) {
    $val_retorno = 1;
    foreach($arr as $val) {
        if ($val['idMateria']==$materia_id) {
            return [2,$val['cursado']];
        }
    };
    return [$val_retorno,''];
}

$objMateria = new Materia();

foreach($arr_en_condiciones_de_cursar as $val_en_condiciones_cursar) {
        //var_dump($arr_en_condiciones_de_cursar,$val_en_condiciones_cursar);exit;
        $arr_datos_materia = [];
        $arr_item = [];
        if ($icm->verificarExcepciones($idAlumno, $val_en_condiciones_cursar['materia_id'])) {
            
            $arr_item['estado'] = has_estado($val_en_condiciones_cursar['materia_id'],$arreglo_materias_inscriptas);
            
            $arr_item['nombre'] = $val_en_condiciones_cursar['nombre'];
            $arr_item['materia_id'] = $val_en_condiciones_cursar['materia_id'];
            $arr_item['anio'] = $val_en_condiciones_cursar['anio'];

            $arr_datos_materia = $objMateria->getMateriaDetalleById($arr_item['materia_id']);
            $arr_item['materia_formato_codigo'] = $arr_datos_materia['formato_codigo'];
            $arr_item['materia_formato_id'] = $arr_datos_materia['idFormato'];
            $arr_item['materia_formato_nombre'] = $arr_datos_materia['formato_nombre'];
            $arr_item['materia_cursado_codigo'] = $arr_datos_materia['cursado_codigo'];
            $arr_item['materia_cursado_id'] = $arr_datos_materia['idCursado'];
            $arr_item['materia_cursado_nombre'] = $arr_datos_materia['cursado_nombre'];

            $arr_resultado[] = $arr_item;
        }
}

//var_dump($arr_resultado);die;
echo json_encode($arr_resultado);

?>
