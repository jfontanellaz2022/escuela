<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once "Sanitize.class.php";
require_once "AlumnoRindeMateria.php";


$accion = $_POST['accion'];
$id = SanitizeVars::INT($_POST['id']);
$idAlumno = SanitizeVars::INT($_POST['alumno_id']);
$idMateria = SanitizeVars::INT($_POST['materia_id']);
$idCalendario = SanitizeVars::INT($_POST['calendario_id']);
$llamado_nro = SanitizeVars::INT($_POST['llamado_nro']);
$nota = SanitizeVars::INT($_POST['nota']);
$estado_final = SanitizeVars::STRING($_POST['estado_final']);
$condicion = SanitizeVars::STRING($_POST['condicion']);
$fecha = $_POST['fecha'];

$usuario = $_SESSION['arreglo_datos_usuario']['id'];
//var_dump($_SESSION['arreglo_datos_usuario']);exit;
$array_resultados = array();

if ($accion && $idAlumno && $idCalendario && $estado_final && $estado_final && $estado_final && $condicion && $fecha) {

  

    $alumno_rinde_materia = new AlumnoRindeMateria();
    if ($accion=='Editar') {
      //die('entrooo editar');
        if ($id) {

            $alumno_rinde_materia->save(["id"=>$id,"alumno_id"=>$idAlumno ,"materia_id"=>$idMateria, "calendario_id"=>$idCalendario,
                                         "llamado"=>$llamado_nro,"nota"=>$nota,"condicion"=>$condicion,
                                         "estado_final"=>$estado_final,"fecha_hora_inscripcion"=>$fecha,"usuario_id"=>$usuario]);
            $array_resultados['codigo'] = 200;
            $array_resultados['datos'] = "Registro Actualizado con éxito."; 

                                        } else {
            $array_resultados['codigo'] = 500;
            $array_resultados['datos'] = "Faltan Datos Obligatarios.";
        };

    } else {
        $alumno_rinde_materia->save(["alumno_id"=>$idAlumno, "materia_id"=>$idMateria, "calendario_id"=>$idCalendario,
                                     "llamado"=>$llamado_nro,"nota"=>$nota,"condicion"=>$condicion,
                                     "estado_final"=>$estado_final,"fecha_hora_inscripcion"=>$fecha,"usuario_id"=>$usuario]);
        $array_resultados['codigo'] = 200;
        $array_resultados['datos'] = "Registro Creado con éxito.";  

    }

} else {
    $array_resultados['codigo'] = 500;
    $array_resultados['data'] = "Faltan Datos Obligatarios.";
}

echo json_encode($array_resultados);

?>
