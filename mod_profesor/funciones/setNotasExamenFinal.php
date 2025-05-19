<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$materia_id = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$alumno_id = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$nota = ( isset($_POST['nota']) && in_array($_POST['nota'],array(1,2,3,4,5,6,7,8,9,10,0,-1,-2)) )?$_POST['nota']:false;
$estadoFinal = (isset($_POST['estadoFinal']) && in_array($_POST['estadoFinal'],array('Pendiente','Aprobo','Ausente','Suspenso','Desaprobo')))?$_POST['estadoFinal']:false;
$calendario_id = (isset($_POST['calendario']) && $_POST['calendario']!=NULL)?SanitizeVars::INT($_POST['calendario']):false;
$llamado = (isset($_POST['llamado']) && $_POST['llamado']!=NULL)?SanitizeVars::INT($_POST['llamado']):false;

//var_dump($materia_id.$alumno_id.$nota);exit;

$hoy=date('Y-m-d H:i:s');

$array_resultados = array();
if ($materia_id && $alumno_id && $nota && $estadoFinal && $calendario_id && $llamado) {
      $sql = "UPDATE alumno_rinde_materia
              SET nota = $nota,
                  estado_final = '$estadoFinal',
                  FechaModificacionNota = '$hoy'
              WHERE idCalendario = $calendario_id and 
                    llamado = $llamado and 
                    idAlumno = $alumno_id and
                    idMateria = $materia_id";
      //die($sql);exit;              
      $resultado = mysqli_query($conex,$sql);                
     
      if ($resultado) {
         $array_resultados['codigo'] = 100;
         $array_resultados['data'] = "La Nota del Alumno con ID <strong>$alumno_id</strong>, fue cargada exitosamente.";
      } else {
         $errorNro =  mysqli_errno($conex);
         $array_resultados['codigo'] = 12;
        $array_resultados['data'] = "Hubo un Error en el Alta de la Nota del Alumno. ";
      }
} else {
      $array_resultados['codigo'] = 13;
      $array_resultados['data'] = "Faltan Datos para realizar la carga. ";
}
echo json_encode($array_resultados);



?>
