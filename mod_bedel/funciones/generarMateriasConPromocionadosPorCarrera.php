<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'AlumnoRindeMateria.php';

//$parametros = explode('_',$_GET['parametros']);
$parametros = $_GET['parametros'];
$arrCarrera=explode('_',$parametros);
$idCarrera = $arrCarrera[0];
$idCalendario = $arrCarrera[1];
$fecha_acta = $arrCarrera[2];

$arrayMateriasPromocionadasPorCarrera=array();
$arraytodasMateriasConPromocionadosPorCarrera=array();

$objARM = new AlumnoRindeMateria();

$arr_materias_por_carrera = $objARM->getMateriasConInscriptosPromocionadosPorCarrera($idCalendario,$idCarrera);

if (count($arr_materias_por_carrera)>0) {
    foreach ($arr_materias_por_carrera as $filaMateriasPorCarrera) {
      $arrayMateriaPorCarrera = array(); 
      array_push($arrayMateriaPorCarrera,$filaMateriasPorCarrera['id'],$filaMateriasPorCarrera['nombre'],$filaMateriasPorCarrera['anio'],$filaMateriasPorCarrera['cantidad']);
      array_push($arraytodasMateriasConPromocionadosPorCarrera,$arrayMateriaPorCarrera);
    }
}

echo "<table class=\"table\">";
$band=true;

function sacaMateriaPorAnio($anio) {
 global $arraytodasMateriasConPromocionadosPorCarrera;
 global  $parametros,$fecha;
 $band=false;$str="";
 foreach ($arraytodasMateriasConPromocionadosPorCarrera as $valor) {
   if ($valor[2]==$anio) {
     $param = base64_encode($parametros.'_'.$valor[0]);
     /*$str.="<tr onmouseover=\"cambiar_color_over(this)\" onmouseout=\"cambiar_color_out(this)\" "
          ." onclick=\"window.open('./funciones/PDF_ActaPromocionados.php?parametros=".$param."','_blank')\">"
          ."<td style='text-align: left;'>$valor[1]</td><td style='text-align: center;'>$valor[3]</td></tr>";*/
     $str.= "<tr id='tr_{$parametros}_{$valor[0]}' >"
          . " <td style='text-align: left;'><a href=\"../API/reporteActaPromocionados.php?token=" . $_SESSION['token'] . "&parametros=$param\" target=\"_blank\"><img src=\"../public/img/icons/listado_icon.png\" width=\"25\"></a>&nbsp;$valor[1] <strong>($valor[0])</strong></td>" 
          . " <td style='text-align: center;'>$valor[3]</td> " 
          . "</tr> ";
     $band=true;
     }
 }
 if ($band) return $str;
 else return "<tr><td style='text-align: center;color:red' colspan='2'>No Existen Alumnos Promocionados en este A&ntilde;o</td></tr>";
}


          echo "<tr><th colspan=2 style='text-align: center;background-color: #80A5EF;'>Primer A&ntilde;o</th></tr>";
          echo "<tr><th style='text-align: center;'>Asignatura</th><th style='text-align: center;'>Cantidad Inscriptos</th></tr>";
          echo sacaMateriaPorAnio(1);
          echo "<tr><th colspan=2 style='text-align: center;background-color: #80A5EF;' >Segundo A&ntilde;o</th></tr>";
          echo "<tr><th style='text-align: center;'>Asignatura</th><th style='text-align: center;'>Cantidad Inscriptos</th></tr>";
          echo sacaMateriaPorAnio(2);
          echo "<tr><th colspan=2 style='text-align: center;background-color: #80A5EF;' >Tercer A&ntilde;o</th></tr>";
          echo "<tr><th style='text-align: center;'>Asignatura</th><th style='text-align: center;'>Cantidad Inscriptos</th></tr>";
          echo sacaMateriaPorAnio(3);
          echo "<tr><th colspan=2 style='text-align: center;background-color: #80A5EF;' >Cuarto A&ntilde;o</th></tr>";
          echo "<tr><th style='text-align: center;'>Asignatura</th><th style='text-align: center;'>Cantidad Inscriptos</th></tr>";
          echo sacaMateriaPorAnio(4);
     
      
echo "</table>";
    
?>

