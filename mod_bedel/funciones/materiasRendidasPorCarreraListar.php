<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'pagination.php';
require_once 'AlumnoRindeMateria.php';

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$idCarrera = isset($_POST['carrera_id'])?SanitizeVars::INT($_POST['carrera_id']):FALSE;
$idAlumno = isset($_POST['alumno_id'])?SanitizeVars::INT($_POST['alumno_id']):FALSE;

$objeto = new AlumnoRindeMateria();
$arr_datos = $objeto->getHistorialMateriasRendidasByAlumnoByCarrera($idAlumno,$idCarrera);
//var_dump($arr_datos);exit;
if($action == 'listar' && $idCarrera){

	if (count($arr_datos)>0){
		$indice=0;
			echo '<div class="table-responsive" ">
				<table class="table table-striped table-bordered table-hover" id="tabla_calendario">
					<thead class="thead-dark">
						<tr>
							<th class="text-center" width="5%">#</th>
							<th class="text-center" width="35%"><small><b>MATERIA</b><small></th>
							<th class="text-center" width="4%"><small><b>AÑO</b><small></th>
							<th class="text-center" width="35%"><small><b>EVENTO</b><small></th>
							<th class="text-center" width="4%"><small><b>LLAMADO</b><small></th>
							<th class="text-center" width="8%"><small><b>CONDICIÓN</b><small></th>
							<th class="text-center" width="6%"><small><b>NOTA</b><small></th>
							<th class="text-center" width="10%"><small><b>ESTADO</b><small></th>
							<th width="25%" class="text-center"><small><b>ACCIONES</b><small></th>
						</tr>
					</thead>';
		echo '<tbody>';
	    //$tipo_organismo = substr($_SESSION['organismo_codigo'],0,1);
	$badge = '';
    foreach ($arr_datos as $row) {
			$indice++;
			$espacio = "&nbsp;";
			//$accion_editar = '<a href="#" class="disabledbutton" onclick="rendidaEditar(\''.$idAlumno.'&'.$row['materia_id'].'&'.$row['materia_nombre'].'&'.$row['materia_anio'].'&'.$row['calendario_id'].'&'.$row['llamado'].'&'.$row['nota'].'&'.$row['estado_final'].'&'.$row['condicion'].'&'.$row['fecha_hora_inscripcion'].'&'.$row['evento_nombre'].'&'.$row['id'].'\')" title="Editar"><img src="../public/img/icons/edit_icon.png" width="20"></a>';
			$accion_eliminar = '<a href="#" onclick="rendidaEliminar('.$row['id'].')" title="Eliminar"><img src="../public/img/icons/delete_icon.png" width="15"></a>';
			$nota = "";
			if ($row['nota']=='-1') {
				$nota = "---";
			} else if ($row['nota']=='0') {
				$nota = "...";
			} else {
				$nota = $row['nota'];
			}
			
			if ($row['estado_final']=='Aprobo') {
				$badge = 'badge-success';
			} else if ($row['estado_final']=='Desaprobo') {
				$badge = 'badge-danger';
			} else if ($row['estado_final']=='Ausente') {
				$badge = 'badge-secondary';
			} else if ($row['estado_final']=='Pendiente') {
				$badge = 'badge-warning';
			} else {
				$badge = 'badge-info';
			}
        	echo '<tr>';
			echo '   <td align="center">'.'<b>'.$indice.'</b></td>'.
				 '   <td align="left"><small>'.$row['materia_nombre'].'&nbsp;<b>('.$row['materia_id'].')</b></small></td>'.
				 '   <td align="center"><small>'.$row['materia_anio'].'</small></td>'.
				 '   <td align="left"><small>'.$row['evento_nombre'].' <strong>('.$row['calendario_id'].')</strong></small></td>'.
				 '   <td align="center"><small>'.$row['llamado'].'</small></td>'.
				 '   <td align="left"><small>'.$row['condicion'].'</small></td>'.
				 '   <td align="center"><small><strong>'.$nota.'</strong></small></td>'.
				 '   <td align="center"><small><span class="badge '.$badge.'">'.$row['estado_final'].'</span></small></td>'.
				 '   <td align="center" class="text-center"><small>'.$accion_eliminar.'</small></td>';
			echo '</tr>';
    };
	echo "</tbody><tfoot><tr><td colspan='9'>";
		echo "</td></tr>";
    echo '</tfoot>';
	echo '</table>';
} else {
	echo '<table class="table" id="tabla_calendario">';
	echo '<tbody>';
	echo '<tr><td><div class="alert alert-danger" role="alert">
				 <b>Atenci&oacute;n:</b> No existen Materias Rendidas.
			 </div></td></tr>';
	echo '</tbody>';
	echo '<tfoot></tfoot>';
	echo '</table>';
};
};

?>
