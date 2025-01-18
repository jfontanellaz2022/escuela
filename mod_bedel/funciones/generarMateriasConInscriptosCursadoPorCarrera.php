<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');
include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$parametros = $_GET['parametros'];
//var_dump($parametros);die;
$idCarrera = $parametros;
$arrayMateriasAprobadasPorCarrera = array();
$anioActual = date('Y');
$sqlMateriasPorCarrera = "SELECT DISTINCT c.id, c.nombre, COUNT( * ) as cantidad, c.anio
                          FROM alumno_cursa_materia a, carrera_tiene_materia b, materia c
                          WHERE a.idMateria = b.idMateria AND
                                b.idCarrera = {$idCarrera} AND
                                b.idMateria = c.id AND
                                a.anioCursado = $anioActual 
                          GROUP BY c.nombre
                          ORDER BY c.anio";

//echo $sqlMateriasPorCarrera;die;
$resultadoMateriasPorCarrera=mysqli_query($conex,$sqlMateriasPorCarrera);
$arraytodasMateriasConInscriptosPorCarrera=array();
while ($filaMateriasPorCarrera=mysqli_fetch_assoc($resultadoMateriasPorCarrera)) {
    $arrayMateriaPorCarrera=array(); 
    array_push($arrayMateriaPorCarrera,$filaMateriasPorCarrera['id'],$filaMateriasPorCarrera['nombre'],$filaMateriasPorCarrera['anio'],$filaMateriasPorCarrera['cantidad']);
    array_push($arraytodasMateriasConInscriptosPorCarrera,$arrayMateriaPorCarrera);
}
    
echo "<table class=\"table table-stripped\">";
$band=true;

function sacaMateriaPorAnio($anio) {
    global $arraytodasMateriasConInscriptosPorCarrera;
    global $parametros;
    $band=false;$str="";
    foreach ($arraytodasMateriasConInscriptosPorCarrera as $valor) {
    if ($valor[2]==$anio) {
        $str.="<tr>" .
              " <td style='text-align: left;'>$valor[1] <strong>($valor[0])</strong></td><td style='text-align: center;'><strong><a href='#' onclick=\"cargaInscriptosCursadoPorMateria($valor[0],'$valor[1]')\" >$valor[3]</a></strong></td></tr>";
        $band=true;
        }
    }
    if ($band) return $str;
    else return "<tr><td style='text-align: center;color:red' colspan='2'>No Existen Alumnos que rindan materias de este A&ntilde;o</td></tr>";
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