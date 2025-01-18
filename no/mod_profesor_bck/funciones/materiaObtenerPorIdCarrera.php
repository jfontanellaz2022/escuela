<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'./');

require_once "ProfesorDictaMateriaDetalle.php";
//require_once "CarreraTieneMateria.php";
//require_once "Materia.php";
//require_once "MateriaCondicion.php";
//require_once "MateriaFormato.php";
//require_once "MateriaPeriodoCursado.php";
require_once "Sanitize.class.php";
require_once "_seguridad.php";

$idProfesor = $_SESSION['idProfesor'];
$idCarrera = (isset($_POST['carrera']) && $_POST['carrera']!=NULL)?SanitizeVars::INT($_POST['carrera']):false;

$arr_resultados = $arr_materias = [];

if ($idCarrera && $idProfesor) {
    
    $objeto = new ProfesorDictaMateriaDetalle();
    $arr_profesor_materias = $objeto->getMateriasByIdProfesor($idProfesor);
    
    //var_dump($arr_profesor_materias);die; 
    
    foreach ($arr_profesor_materias as $val) {

        if ($val['carrera_id']==$idCarrera) {
            $arr_item = [];
            $id = $val['materia_id'];
            $nombre = $val['materia_nombre'];
            $anio = $val['materia_anio'];
            $cursado_descripcion = $val['cursado_nombre'];
            $formato_descripcion = $val['formato_nombre'];
            
            $arr_item['materia_id'] = $id; 
            $arr_item['materia_nombre'] = $nombre; 
            $arr_item['materia_anio'] = $anio;
            $arr_item['descripcion_cursado'] = $cursado_descripcion;
            $arr_item['descripcion_formato'] = $formato_descripcion;
            
            $arr_materias[] = $arr_item;
        }
    }
    
    $arr_resultados['codigo'] = 100;
    $arr_resultados['datos'] = $arr_materias;

}

echo json_encode($arr_resultados);
?>
