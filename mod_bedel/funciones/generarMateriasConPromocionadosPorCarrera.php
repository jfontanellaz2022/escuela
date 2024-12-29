<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

//require('own_recuperarEventoTurnoExamenCalendarioAcademico.php');

$parametros = explode('_',$_GET['parametros']);
$idCarrera = $parametros[0];
$idCalendario = $parametros[1];

$arrayMateriasPromocionadasPorCarrera=array();
$sqlMateriasPorCarrera="SELECT DISTINCT c.id, c.nombre, COUNT( * ) as cantidad, c.anio
                        FROM alumno_rinde_materia a, carrera_tiene_materia b, materia c
                        WHERE a.idCalendario = $idCalendario  AND
                              a.condicion ='Promocion' AND
                              a.idMateria = b.idMateria AND
                              b.idCarrera = $idCarrera AND 
                              b.idMateria = c.id
                        GROUP BY c.nombre
                        ORDER BY c.anio";

//echo $sqlMateriasPorCarrera;die;                      
$resultadoMateriasPorCarrera=mysqli_query($conex,$sqlMateriasPorCarrera);
$arraytodasMateriasConPromocionadosPorCarrera=array();
while ($filaMateriasPorCarrera=mysqli_fetch_assoc($resultadoMateriasPorCarrera)) {
    $arrayMateriaPorCarrera=array(); 
        array_push($arrayMateriaPorCarrera,$filaMateriasPorCarrera['id'],$filaMateriasPorCarrera['nombre'],$filaMateriasPorCarrera['anio'],$filaMateriasPorCarrera['cantidad']);
        array_push($arraytodasMateriasConPromocionadosPorCarrera,$arrayMateriaPorCarrera);
            
    }
   
    
echo "<table class=\"pgui-grid grid legacy stripped\">";
$band=true;

function sacaMateriaPorAnio($anio) {
 global $arraytodasMateriasConPromocionadosPorCarrera;
 global $idCarrera;
 global $idCalendario;
 $band=false;$str="";
 foreach ($arraytodasMateriasConPromocionadosPorCarrera as $valor) {
   if ($valor[2]==$anio) {
     $str.="<tr onmouseover=\"cambiar_color_over(this)\" onmouseout=\"cambiar_color_out(this)\" "
          ." onclick=\"window.open('./funciones/PDF_ActaPromocionados.php?parametros=".$idCarrera.'_'.$idCalendario.'_'.$valor[0]."','_blank')\">"
          ."<td style='text-align: left;'>$valor[1]</td><td style='text-align: center;'>$valor[3]</td></tr>";
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

