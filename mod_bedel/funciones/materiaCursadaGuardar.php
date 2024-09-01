<?php
/*
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$idCarrera = SanitizeVars::INT($_POST['carrera_id']);
$idMateria = SanitizeVars::INT($_POST['materia_id']);
$idAlumno = SanitizeVars::INT($_POST['alumno_id']);

$nota = SanitizeVars::INT($_POST['nota']);
$array_resultados = array();

function getCodigoCalendarioHomologacion($conex) {
   $sql = "SELECT id FROM calendarioacademico WHERE idEvento = 1026";
   $resultado = mysqli_query($conex,$sql);
   $idCalendario = 0;
   if ($resultado) {
       $fila = mysqli_fetch_assoc($resultado);
       $idCalendario = $fila['id'];
   };
   return $idCalendario;
}


$idCalendario = getCodigoCalendarioHomologacion($conex);
$ahora = date("Y-m-d H:i:s");
if ($idAlumno && $idMateria && $idAlumno && $nota && $idCalendario) {
    $sql = "INSERT INTO alumno_rinde_materia(idAlumno,idMateria,idCalendario,llamado,condicion,FechaHoraInscripcion,nota,estado_final, FechaModificacionNota) " .
           "VALUES ($idAlumno,$idMateria,$idCalendario,1,'Homologacion','$ahora',$nota,'Aprobo','$ahora') ";
    $resultado = mysqli_query($conex,$sql);
    if (mysqli_affected_rows($conex)!=-1) {
      $array_resultados['codigo'] = 100;
      $array_resultados['data'] = "La Homologación se ha realizado.";
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['data'] = "La Homologación NO se ha realizado.";
    }
} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['data'] = "Faltan Datos Obligatarios.";
}
echo json_encode($array_resultados);*/

set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

require_once 'AlumnoCursaMateria.php';
require_once 'Sanitize.class.php';
require_once "_seguridad.php";

$accion = $_POST['accion'];
$id = SanitizeVars::INT($_POST['id']);
$idAlumno = SanitizeVars::INT($_POST['alumno_id']);
$idMateria = SanitizeVars::INT($_POST['materia_id']);
$cursado_anio = SanitizeVars::INT($_POST['cursado_anio']);
$cursado_id = SanitizeVars::INT($_POST['cursado_id']);
$cursado_nombre = SanitizeVars::STRING($_POST['cursado_nombre']);
$nota = SanitizeVars::INT($_POST['nota']);
$estado_final = SanitizeVars::STRING($_POST['estado_final']);
$fecha_hora_inscripcion = date('Y-m-d');
$fecha_modificacion_nota = date('Y-m-d');
$fecha_vencimiento_regularidad = ($_POST['fecha_expiracion']!="")?$_POST['fecha_expiracion']:NULL;

$usuario = $_SESSION['idBedel'];

$array_resultados = array();

if ($accion && $idAlumno && $idMateria && $cursado_anio!="" && $cursado_id!="" && $cursado_nombre!="" && $nota!="" && 
    $estado_final && $fecha_hora_inscripcion && $fecha_modificacion_nota && $usuario) {
    //die('Entro');  
    $alumno_cursa_materia = new AlumnoCursaMateria();

    if ($accion=='Editar') {
        if ($id) {
            $arr = ["id"=>$id,"alumno_id"=>$idAlumno ,"materia_id"=>$idMateria, "anio_cursado"=>$cursado_anio,
                    "tipo"=>$cursado_nombre,"cursado_id"=>$cursado_id,"nota"=>$nota,"estado_final"=>$estado_final,
                    "fecha_hora_inscripcion"=>$fecha_hora_inscripcion,"fecha_modificacion_nota"=>$fecha_modificacion_nota,
                    "fecha_vencimiento_regularidad"=>$fecha_vencimiento_regularidad, "usuario_id"=>$usuario];
            
            //var_dump($arr);die;
            $alumno_cursa_materia->save($arr);
            $resultado = $alumno_cursa_materia->save($arr);
            if ($resultado) {
                $array_resultados['codigo'] = 100;
                $array_resultados['datos'] = "Registro Actualizado con éxito.";  
            } else {
                $array_resultados['codigo'] = 10;
                $array_resultados['datos'] = "No se pudo Actualizar el Registrooooo.";  
                                         }
        };

    } else {
        $arr = ["alumno_id"=>$idAlumno ,"materia_id"=>$idMateria, "anio_cursado"=>$cursado_anio,
                "tipo"=>$cursado_nombre,"cursado_id"=>$cursado_id,"nota"=>$nota,"estado_final"=>$estado_final,
                "fecha_hora_inscripcion"=>$fecha_hora_inscripcion,"fecha_modificacion_nota"=>$fecha_modificacion_nota,
                "usuario_id"=>$usuario];
        if ($fecha_vencimiento_regularidad) {
           $arr["fecha_vencimiento_regularidad"] = $fecha_vencimiento_regularidad;
        }        

        $resultado = $alumno_cursa_materia->save($arr);
        if ($resultado) {
            $array_resultados['codigo'] = 100;
            $array_resultados['datos'] = "Registro Creado con éxito.";  
        } else {
            $array_resultados['codigo'] = 10;
            $array_resultados['datos'] = "No se pudo crear un Registro nuevo.";  
        }
        

    }

} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['data'] = "Faltan Datos Obligatarios.";
}

echo json_encode($array_resultados);





?>
