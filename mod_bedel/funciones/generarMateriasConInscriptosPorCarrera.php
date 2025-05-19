<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once "AlumnoRindeMateria.php";



$parametros=$_GET['parametros'];
$arrCarreraTurnoLlamado=explode('_',$parametros);
$idCarrera=$arrCarreraTurnoLlamado[0];
$idCalendario = $arrCarreraTurnoLlamado[1];
$llamado = $arrCarreraTurnoLlamado[2];
$fecha = $arrCarreraTurnoLlamado[3];
$arrayMateriasAprobadasPorCarrera = $arraytodasMateriasConInscriptosPorCarrera = [];

$objARM = new AlumnoRindeMateria();
$arr_materias_por_carrera = $objARM->getMateriasConInscriptosExamenPorCarrera($idCalendario,$idCarrera,$llamado);

if (count($arr_materias_por_carrera)>0) {
    foreach ($arr_materias_por_carrera as $filaMateriasPorCarrera) {
      $arrayMateriaPorCarrera = array(); 
      array_push($arrayMateriaPorCarrera,$filaMateriasPorCarrera['id'],$filaMateriasPorCarrera['nombre'],$filaMateriasPorCarrera['anio'],$filaMateriasPorCarrera['cantidad']);
      array_push($arraytodasMateriasConInscriptosPorCarrera,$arrayMateriaPorCarrera);
    }
}
   

echo "<table class=\"table\">";
$band=true;

function sacaMateriaPorAnio($anio) {
 global $arraytodasMateriasConInscriptosPorCarrera;
 global $parametros,$fecha;
 $band=false;$str="";
 
 foreach ($arraytodasMateriasConInscriptosPorCarrera as $valor) {
   if ($valor[2]==$anio) {
     $param = base64_encode($parametros.'_'.$valor[0]);
     $str.= "<tr id='tr_{$parametros}_{$valor[0]}' >"
          . " <td style='text-align: left;'><a href=\"../API/reporteActaExamenes.php?token=" . $_SESSION['token'] . "&parametros=$param\" target=\"_blank\"><img src=\"../public/img/icons/listado_icon.png\" width=\"25\"></a>&nbsp;$valor[1] <strong>($valor[0])</strong></td>" 
          . " <td style='text-align: center;'>$valor[3]</td> " 
          . "</tr> ";
     $band=true;
     }
 }
 if ($band) return $str;
 else return "<tr><td style='text-align: center;color:red' colspan='2'><strong><i>No Existen Alumnos que rindan materias de este A&ntilde;o</i></strong></td></tr>";
}

echo "<tr><th colspan=2 style='text-align: center;background-color: #80A5EF;'>PRIMER A&Ntilde;O</th></tr>";
echo "<tr><th style='text-align: center;'>Asignatura</th><th style='text-align: center;'>Cantidad Inscriptos</th></tr>";
echo sacaMateriaPorAnio(1);
echo "<tr><th colspan=2 style='text-align: center;background-color: #80A5EF;' >SEGUNDO A&Ntilde;O</th></tr>";
echo "<tr><th style='text-align: center;'>Asignatura</th><th style='text-align: center;'>Cantidad Inscriptos</th></tr>";
echo sacaMateriaPorAnio(2);
echo "<tr><th colspan=2 style='text-align: center;background-color: #80A5EF;' >TERCER A&Ntilde;O</th></tr>";
echo "<tr><th style='text-align: center;'>Asignatura</th><th style='text-align: center;'>Cantidad Inscriptos</th></tr>";
echo sacaMateriaPorAnio(3);
echo "<tr><th colspan=2 style='text-align: center;background-color: #80A5EF;' >CUARTO A&Ntilde;O</th></tr>";
echo "<tr><th style='text-align: center;'>Asignatura</th><th style='text-align: center;'>Cantidad Inscriptos</th></tr>";
echo sacaMateriaPorAnio(4);
echo "</table>";
    
?>