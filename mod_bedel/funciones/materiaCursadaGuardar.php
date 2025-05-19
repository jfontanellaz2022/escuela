<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once "Sanitize.class.php";
require_once "AlumnoCursaMateria.php";

$id = SanitizeVars::INT($_POST['id']);
$idAlumno = SanitizeVars::INT($_POST['alumno_id']);
$idMateria = SanitizeVars::INT($_POST['materia_id']);
$cursado_anio = SanitizeVars::INT($_POST['cursado_anio']);
$cursado_id = SanitizeVars::INT($_POST['cursado_id']);
$cursado_nombre = SanitizeVars::STRING($_POST['cursado_nombre']);
$nota = SanitizeVars::INT($_POST['nota']);
$estado_id = SanitizeVars::STRING($_POST['estado_id']);
$estado_nombre = SanitizeVars::STRING($_POST['estado_nombre']);
$fecha_hora_inscripcion = date('Y-m-d');
$fecha_modificacion_nota = date('Y-m-d');
$fecha_vencimiento_regularidad = ($_POST['fecha_expiracion']!="")?$_POST['fecha_expiracion']:NULL;

$usuario = $_SESSION['arreglo_datos_usuario']['id'];
$array_resultados = array();

if ($idAlumno && $idMateria && $cursado_anio!="" && $cursado_id!="" && $cursado_nombre!="" && $nota!="" && 
    $estado_id && $estado_nombre && $fecha_hora_inscripcion && $fecha_modificacion_nota && $usuario && $fecha_vencimiento_regularidad) {
    $alumno_cursa_materia = new AlumnoCursaMateria();
    $cursado_nombre_tmp = explode(" ",$cursado_nombre);
    $cursado_nombre = $cursado_nombre_tmp[0];
    $estado_nombre_tmp = explode(" ",$estado_nombre);
    $estado_nombre = $estado_nombre_tmp[0];


    if ($id) {
            
        $arr = ["id"=>$id,"alumno_id"=>$idAlumno ,"materia_id"=>$idMateria, "anio_cursado"=>$cursado_anio,
                "cursado_nombre"=>$cursado_nombre,"cursado_id"=>$cursado_id,"nota"=>$nota,"estado_id"=>$estado_id,"estado_nombre"=>$estado_nombre,
                "fecha_inscripcion"=>$fecha_hora_inscripcion,"fecha_modificacion_nota"=>$fecha_modificacion_nota,
                "fecha_vencimiento_regularidad"=>$fecha_vencimiento_regularidad, "usuario_id"=>$usuario];
            
        //var_dump($arr);die;
        $alumno_cursa_materia->save($arr);
        $resultado = $alumno_cursa_materia->save($arr);
        if ($resultado) {
                $array_resultados['codigo'] = 200;
                $array_resultados['datos'] = "Registro Actualizado con éxito.";  
        } else {
                $array_resultados['codigo'] = 500;
                $array_resultados['datos'] = "No se pudo Actualizar el Registrooooo.";  
        }
    } else {
        $arr = ["alumno_id"=>$idAlumno ,"materia_id"=>$idMateria, "anio_cursado"=>$cursado_anio,
                "cursado_nombre"=>$cursado_nombre,"cursado_id"=>$cursado_id,"nota"=>$nota,"estado_id"=>$estado_id,"estado_nombre"=>$estado_nombre,
                "fecha_inscripcion"=>$fecha_hora_inscripcion,"fecha_modificacion_nota"=>$fecha_modificacion_nota,"fecha_vencimiento_regularidad"=>$fecha_vencimiento_regularidad,
                "usuario_id"=>$usuario];
        //var_dump($arr);die;
       
        $resultado = $alumno_cursa_materia->save($arr);
        if ($resultado) {
            $array_resultados['codigo'] = 200;
            $array_resultados['datos'] = "Registro Creado con éxito.";  
        } else {
            $array_resultados['codigo'] = 500;
            $array_resultados['datos'] = "No se pudo crear un Registro nuevo.";  
        }
        

    }

} else {
    $array_resultados['codigo'] = 200;
    $array_resultados['data'] = "Faltan Datos Obligatarios.";
}

echo json_encode($array_resultados);





?>
