<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idMateria = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$nota = ( isset($_POST['nota']) && in_array($_POST['nota'],array(1,2,3,4,5,6,7,8,9,10,0,-1,-2)) )?$_POST['nota']:false;
$estadoFinal = (isset($_POST['estadoFinal']) && in_array($_POST['estadoFinal'],array('Cursando','Regularizo','Promociono','Aprobo','Libre','Homologo','Suspenso')))?$_POST['estadoFinal']:false;

$anioActual=date('Y');
$hoy=date('Y-m-d H:i:s');
$estado_final="";
$fechaVencimientoRegularidad = null;


$array_resultados = array();
if ($idMateria && $idAlumno && $nota && $estadoFinal) {
      if ($estadoFinal=='Regularizo') {
          $anio_actual = date('Y')+4; 
          $fechaVencimientoRegularidad = $anio_actual.'-04-01';
      } else if ($estadoFinal=='Libre') {
          $anio_actual = date('Y')+1; 
          $fechaVencimientoRegularidad = $anio_actual.'-04-01';
      };      
      $sql = "UPDATE alumno_cursa_materia
              SET nota = $nota,
                  estado_final = '$estadoFinal',
                  fechaModificacionNota = '$hoy',
                  FechaVencimientoRegularidad = '$fechaVencimientoRegularidad'
              WHERE idAlumno = $idAlumno and
                    idMateria = $idMateria and
                    anioCursado = $anioActual";
      $resultado = mysqli_query($conex,$sql);                
      $sqlAnulaRegularidadesAnteriores =   "DELETE FROM alumno_cursa_materia 
                                            WHERE idAlumno = $idAlumno and
                                                  idMateria = $idMateria and
                                                  anioCursado <> $anioActual";              
      mysqli_query($conex,$sqlAnulaRegularidadesAnteriores); 
      if ($resultado) {
         $array_resultados['codigo'] = 100;
         $array_resultados['data'] = "La Nota del Alumno con ID <strong>$idAlumno</strong>, fue cargado exitosamente.";
      } else {
         $errorNro =  mysqli_errno($conex);
         $array_resultados['codigo'] = 12;
        $array_resultados['data'] = "Hubo un Error en el Alta del Alumno a la Materia. ";
      }
} else {
      $array_resultados['codigo'] = 13;
      $array_resultados['data'] = "Faltan Datos para realizar la carga. ";
}
echo json_encode($array_resultados);



?>
