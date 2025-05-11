<?php
//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** RETORNA SI EL ALUMNO TIENE MESA ESPECIAL EN UNA CARRERA DADA                      **
//***************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../lib');
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$idCarrera = (isset($_POST['carrera']) && $_POST['carrera']!=NULL)?SanitizeVars::INT($_POST['carrera']):false;
$idAlumno = (isset($_POST['alumno']) && $_POST['alumno']!=NULL)?SanitizeVars::INT($_POST['alumno']):false;
$array_resultados = array();
//die($idCarrera.'**'.$idAlumno);
if ($idCarrera && $idAlumno) {
      $sql = "SELECT aec.mesa_especial
              FROM alumno_estudia_carrera aec
              WHERE aec.idCarrera = $idCarrera and 
                    aec.idAlumno = $idAlumno;
              ";
      $resultado = mysqli_query($conex,$sql);
      if (mysqli_num_rows($resultado)>0) {
        $filas = mysqli_fetch_assoc($resultado);
        if ($filas['mesa_especial']=='Si') {
            $array_resultados['codigo'] = 100;
            $array_resultados['data'] = $filas['mesa_especial'];
        } else {
            $array_resultados['codigo'] = 100;
            $array_resultados['data'] = 'No';
          }
      } else {
        $array_resultados['codigo'] = 11;
        $array_resultados['data'] = "No existe Carrera.";
      }
} else {
      $array_resultados['codigo'] = 10;
      $array_resultados['data'] = '[ID] de Carrera Inv&aacute;lido.';
};

echo json_encode($array_resultados);

?>
