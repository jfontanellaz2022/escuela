<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/class/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'Tipificacion.php';
require_once 'CalendarioAcademico.php';
require_once 'AlumnoRindeMateria.php';
require_once 'Constantes.php';

//var_dump($_SESSION['arreglo_datos_usuario']);exit;

//Constantes::CODIGO_HOMOLOGACION;

$idCarrera = SanitizeVars::INT($_POST['carrera_id']);
$idMateria = SanitizeVars::INT($_POST['materia_id']);
$idAlumno = SanitizeVars::INT($_POST['alumno_id']);
$idUsuario = $_SESSION['arreglo_datos_usuario']['id'];
$nota = SanitizeVars::INT($_POST['nota']);

$array_resultados = array();

$objTipificacion = new Tipificacion(); 
$res = $objTipificacion->getTipificacionByCodigo(Constantes::CODIGO_HOMOLOGACION);


$tipificacion_id = $res['id'];
$anio_actual = date('Y');
$ahora = date("Y-m-d");

//var_dump($idCarrera,$idMateria,$idAlumno,$nota);exit;

//$idCalendario = getCodigoCalendarioHomologacion($conex);

if ($idAlumno && $idMateria && $idAlumno && $nota) {
    
    $objCalendario = new CalendarioAcademico();
     
    $param['anio_lectivo'] = $anio_actual;
    $param['idTipificacion'] = $tipificacion_id;
    $param['idUsuario'] = $idUsuario;
    $param['fecha_inicio'] = $ahora;
    $param['fecha_final'] = $ahora;

    $calendario_id = $objCalendario->save($param);

    //var_dump($calendario_id);exit;
    
    if ($calendario_id) {
        $objAlumnoRindeMateria = new AlumnoRindeMateria();
        $param1['alumno_id'] = $idAlumno;
        $param1['materia_id'] = $idMateria;
        $param1['calendario_id'] = $calendario_id;
        $param1['llamado'] = 1;
        $param1['condicion'] = 'Homologacion';
        $param1['fecha_hora_inscripcion'] = date("Y-m-d H:i:s");
        $param1['nota'] = $nota;
        $param1['estado_final'] = 'Aprobo';
        $param1['usuario_id'] = $idUsuario;

        $res_arm = $objAlumnoRindeMateria->save($param1);

        if ($res_arm) {
            $array_resultados['codigo'] = 200;
            $array_resultados['alert'] = "success";
            $array_resultados['mensaje'] = "La Homologación se ha realizado.";
        } else {
            $array_resultados['codigo'] = 500;
            $array_resultados['alert'] = "danger";
            $array_resultados['mensaje'] = "No se ha podido guardar la homologación.";
        }

    }
} else {
    $array_resultados['codigo'] = 500;
    $array_resultados['data'] = "Faltan Datos Obligatorios.";
}
echo json_encode($array_resultados);

?>
