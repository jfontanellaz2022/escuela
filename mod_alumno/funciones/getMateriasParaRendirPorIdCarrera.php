<?php
session_start();
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../lib');
//require_once "../../app/models/InscripcionRendirMaterias.php";

require_once "InscripcionRendirMaterias.php";
require_once "AlumnoRindeMateria.php";
require_once "MateriaFechaExamen.php";
require_once "Constantes.php";
//$idCarrera = $_POST['carrera']; 
//$idAlumno = $_POST['alumno']; 



$idAlumno = $_SESSION['idAlumno'];
$idCalendario = $_SESSION['arr_calendario']['inscripcion_asociada'];
$idTurno = $_SESSION['turno_id'];
$idCarrera = $_POST['carrera_id'];
//die($idAlumno.' '.$idCalendario.' '.$idCarrera);
$arr_resultado = [];

//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
if ($token!=$_SESSION['token']) {
  $finalResponse['codigo'] = 500;
  $finalResponse['class'] = 'danger';
  $finalResponse['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($finalResponse);die;
}
//****************************************************** */

$materia_tiene_fecha = new MateriaFechaExamen();

$irm = new InscripcionRendirMaterias();

$arr_en_condiciones_de_rendir = $irm->getArregloMateriasVerificadasParaInscribirseDetalles($idAlumno,$idCarrera);
//die('test' . $idAlumno . ' - ' . $idCarrera);
$arr_materias_por_tipo_cursado = $irm->getMateriasCandidatasPorTipoCursado();
//var_dump($arr_materias_por_tipo_cursado);exit;

$arm = new AlumnoRindeMateria();
$arreglo_materias_inscriptas = $arm->getMateriasByIdAlumnoByIdCalendario($idAlumno,$idCalendario,$llamado=3);
//var_dump($arreglo_materias_inscriptas);exit;

function has_estado_inscripcion($materia_id,$arr) {
    //1 No esta Inscripta, 2 Ya esta Inscripta, 3 Ya la rindio en ese llamado pero esta em estado 'Pendiente' o 'Desaprobo'
    $val_retorno = 1;
    foreach($arr as $val) {
        if ($val['idMateria']==$materia_id && ($val['estado_final']=='Ausente' || $val['estado_final']=='Desaprobo')) {
            return 3;
        } else if ($val['idMateria']==$materia_id && $val['estado_final']=='Pendiente') {
            $val_retorno = 2;
        }
    };
    return $val_retorno;
}

function getCursado($idMateria,$arr) {
    foreach ($arr as $value) {
         if ($value['idMateria']==$idMateria) {
            if ($value['cursado_codigo']==Constantes::CODIGO_CURSADO_PRESENCIAL) {
                return 'PRESENCIAL';
            } else if ($value['cursado_codigo']==Constantes::CODIGO_CURSADO_SEMIPRESENCIAL) {
                return 'SEMIPRESENCIAL';
            } else if ($value['cursado_codigo']==Constantes::CODIGO_CURSADO_LIBRE) {
                return 'LIBRE';
            }
         };
    }
    return "Sin Estado";
}

function getCondicion($idMateria,$arr) {
    foreach ($arr as $value) {
         if ($value['idMateria']==$idMateria) {
            if ($value['estado_codigo']==Constantes::CODIGO_ESTADO_LIBRE) {
                return 'LIBRE';
            } else {
                return 'REGULAR';
            }
         };
    }
    return "Sin Estado";
}


//var_dump($arr_en_condiciones_de_rendir);exit;
foreach($arr_en_condiciones_de_rendir as $val_en_condiciones_rendir) {
        //var_dump($val_en_condiciones_rendir['materia_id']);die;
        $arr_item = [];
        $arr_item['estado_inscripcion'] = has_estado_inscripcion($val_en_condiciones_rendir['materia_id'],$arreglo_materias_inscriptas);
        $arr_item['nombre'] = $val_en_condiciones_rendir['nombre'];
        $arr_item['materia_id'] = $val_en_condiciones_rendir['materia_id'];
        $arr_item['anio'] = $val_en_condiciones_rendir['anio'];
        $arr_item['cursado'] = getCursado($val_en_condiciones_rendir['materia_id'],$arr_materias_por_tipo_cursado);
        $arr_item['condicion'] = getCondicion($val_en_condiciones_rendir['materia_id'],$arr_materias_por_tipo_cursado);
        $arr_item['fecha'] = $materia_tiene_fecha->getMateriaFechaExamenByIdMateriaByIdCalendario($val_en_condiciones_rendir['materia_id'],$idTurno);
        $arr_resultado[] = $arr_item;
}
echo json_encode($arr_resultado);

?>
