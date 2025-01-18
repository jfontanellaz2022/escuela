<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$idAlumno = (isset($_POST['alumno_id']))?SanitizeVars::INT($_POST['alumno_id']):false;
$idMateria = (isset($_POST['materia_id']))?SanitizeVars::INT($_POST['materia_id']):false;
$idCalendario = (isset($_POST['calendario_id']))?SanitizeVars::INT($_POST['calendario_id']):false;
$llamado = (isset($_POST['llamado']))?SanitizeVars::INT($_POST['llamado']):false;

//var_dump($_POST);die;

$array_resultados = array();

if ($idAlumno && $idMateria && $idCalendario && $llamado) {
      $hoy=date('Y-m-d H:i:s');
      if ($llamado==3) {
            $sqlInsertaMateriaLlamado1 = "INSERT INTO alumno_rinde_materia(idAlumno, condicion,idMateria,idCalendario,llamado,
                                                fechaHoraInscripcion,estado_final,idUsuario)" . 
                                          "VALUES ($idAlumno,'Regular',$idMateria,$idCalendario,1,'$hoy','Pendiente','".$_SESSION['usuario']."')";

            
            $sqlInsertaMateriaLlamado2 = "INSERT INTO alumno_rinde_materia(idAlumno, condicion,idMateria,idCalendario,llamado,
                                                fechaHoraInscripcion,estado_final,idUsuario)" . 
                                         "VALUES ($idAlumno,'Regular',$idMateria,$idCalendario,2,'$hoy','Pendiente','".$_SESSION['usuario']."')";          
            
            //try {
                  $res_1 = mysqli_query($conex, $sqlInsertaMateriaLlamado1);
                  $res_2 = mysqli_query($conex, $sqlInsertaMateriaLlamado2);
            //} catch (Exception $e) {
              //    $array_resultados['codigo'] = 9;
            //      $array_resultados['mensaje'] = "No se Pudo Realizar la Inscripcion."; 
              //    echo json_encode($array_resultados);
              //    die;
            //}      
      } else {
            $sqlInsertaMateriaLlamado = "INSERT INTO alumno_rinde_materia(idAlumno, condicion,idMateria,idCalendario,llamado,
                                                fechaHoraInscripcion,estado_final,idUsuario) VALUES " . 
                                         "($idAlumno,'Regular',$idMateria,$idCalendario,$llamado,'$hoy','Pendiente','')";  
            $res = mysqli_query($conex, $sqlInsertaMateriaLlamado);
            if (mysqli_affected_rows($conex)!= -1) {
                  $array_resultados['codigo'] = 100;
                  $array_resultados['mensaje'] = "La Inscripción se ha Realizado.";
            } else {
                  $array_resultados['codigo'] = 11;
                  $array_resultados['mensaje'] = "La Inscripción No se ha Realizado.";
            }
      };
      
} else {
      $array_resultados['codigo'] = 10;
      $array_resultados['mensaje'] = "Faltan Datos para realizar la inscripción."; 
};      

echo json_encode($array_resultados);


?>
