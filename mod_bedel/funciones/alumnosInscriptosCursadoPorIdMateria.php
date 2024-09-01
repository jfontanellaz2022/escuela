<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'./');

require_once 'AlumnoCursaMateria.php';
require_once 'Alumno.php';
require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once "_seguridad.php";

function capitalizeCadenas($str) {
    $arr = explode(" ",$str);
    $cad_final = ""; 
	$band = (count($arr)>1)?true:false;
	foreach($arr as $item) {
		if ($band) $cad_final .= ucfirst($item).' ';
		else $cad_final = ucfirst($item);
	}
	$cad_final = ($band)?substr($cad_final,0,strlen($cad_final)-1):$cad_final;
    return $cad_final;
}  
  

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$materia_id = isset($_POST['materia_id'])?SanitizeVars::INT($_POST['materia_id']):false;
$materia_nombre = isset($_POST['materia_nombre'])?SanitizeVars::STRING($_POST['materia_nombre']):false;

//die($materia_id.'*'.$materia_nombre);

$arr_alumnos_cursan = [];

$alumno_cursa_materia = new AlumnoCursaMateria();
$arr_alumnos_cursan = $alumno_cursa_materia->getAlumnoCursaMateriaByIdMateria($materia_id);

$anio_actual = date('Y');

//var_dump($arr_alumnos_cursan);die;
echo "<h3>$materia_nombre <strong>($materia_id)</strong></h3>";

echo "<table class='table'>";
if (!empty($arr_alumnos_cursan)){
	$finales = $c = 0;
	echo "<thead><th>ALUMNO</th><th>AÑO</th><th>CURSADO</th><th>ACTA</th></thead>";
	echo "<tbody>";
	foreach ($arr_alumnos_cursan as $fila) {
		if ($fila['anioCursado']==$anio_actual) {
			$c++;
			$alumno = new Alumno();

			$datos_alumno = $alumno->getAlumnoById($fila['idAlumno']);
			$apellido_nombre_id = $datos_alumno['apellido'].', '.$datos_alumno['nombre'].' <strong>('.$datos_alumno['id'].')</strong>';

			echo "<tr>".
					"<td>" . $apellido_nombre_id . "</td>" .
			        "<td>" . $fila['anioCursado'] . "</td>" .
					"<td>" . $fila['tipo'] . "</td>" .	
					"<td><a href='./funciones/PDF_ActaCursado.php' target='_blank'><img src='../public/assets/img/icons/pdf_icon.png' width='30'></a></td>" .	
				  "</tr>";
	    }					
	};
	echo "</tbody>";
} else {
   echo "<tr></tr>";
}
echo "</table>";
?>			
