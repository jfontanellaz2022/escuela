<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once "AlumnoCursaMateria.php";

$carrera_id = $_POST['carrera'];
$fecha_acta = $_POST['fecha'];

$arrayMateriasAprobadasPorCarrera = $arraytodasMateriasConInscriptosPorCarrera = [];
$anio_vigente = date('Y');//$anio_vigente = 2024;
$objACM = new AlumnoCursaMateria();
$arr_materias_por_carrera = $objACM->getMateriasConInscriptosCursadoPorCarrera($carrera_id,$anio_vigente);

if (count($arr_materias_por_carrera)>0) {
    foreach ($arr_materias_por_carrera as $filaMateriasPorCarrera) {
      $arrayMateriaPorCarrera = array(); 
      array_push($arrayMateriaPorCarrera,$filaMateriasPorCarrera['id'],$filaMateriasPorCarrera['nombre'],$filaMateriasPorCarrera['anio'],$filaMateriasPorCarrera['cantidad']);
      array_push($arraytodasMateriasConInscriptosPorCarrera,$arrayMateriaPorCarrera);
    }
}

//var_dump($carrera_id,$fecha,$anio_vigente,$arr_materias_por_carrera);exit;
echo "<table class=\"table\">";
$band=true;

function sacaMateriaPorAnio($anio) {
 global $arraytodasMateriasConInscriptosPorCarrera, $carrera_id,$anio_vigente,$fecha_acta;
 $band=false;
 $str="";
 $array_param = [];

 $array_param[] = $carrera_id;
 $array_param[] = $anio_vigente;
 $array_param[] = $fecha_acta;
 
 //$array_param_cod = base64_encode(json_encode($array_param));
 
 foreach ($arraytodasMateriasConInscriptosPorCarrera as $valor) {
   if ($valor[2]==$anio) {
     $arr_tmp = [];
     $arr_tmp[] = $array_param;
     $arr_tmp[] = $valor[0];

     $param = base64_encode(json_encode($arr_tmp));
     //var_dump($param);die;
     $str.= "<tr>"
          . " <td style='text-align: left;'><a href=\"../API/reporteActaCursado.php?token=" . $_SESSION['token'] . "&parametros=$param\" target=\"_blank\"><img src=\"../public/img/icons/listado_icon.png\" width=\"25\"></a>&nbsp;$valor[1] <strong>($valor[0])</strong></td>" 
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