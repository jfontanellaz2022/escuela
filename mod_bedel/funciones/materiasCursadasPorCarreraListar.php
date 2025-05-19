<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'pagination.php';
require_once 'AlumnoCursaMateria.php';

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$idCarrera = isset($_POST['carrera_id'])?SanitizeVars::INT($_POST['carrera_id']):FALSE;
$idAlumno = isset($_POST['alumno_id'])?SanitizeVars::INT($_POST['alumno_id']):FALSE;

$objeto = new AlumnoCursaMateria();
$arr_datos = $objeto->getHistorialMateriasCursadasByAlumnoByCarrera($idAlumno,$idCarrera);
if($action == 'listar' && $idCarrera){
	//PAGINATION VARIABLES
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:10; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	//Count the total number of row in your table*/

	//main query to fetch the data

	//die($sql_2);
	//loop through fetched data
	if (count($arr_datos)>0){
		$c=0;
			echo '<div class="table-responsive" ">
				<table class="table table-striped table-bordered table-hover" id="tabla_materias_cursadas">
					<thead class="thead-dark">
						<tr>
							<th class="text-center" width="5%">#</th>
							<th class="text-center" width="40%"><small><b>MATERIAS</b><small></th>
							<th class="text-center" width="10%"><small><b>AÑO</b><small></th>
							<th class="text-center" width="10%"><small><b>T.CURSADO</b><small></th>
							<th class="text-center" width="10%"><small><b>AÑO CURSADO</b><small></th>
							<th class="text-center" width="10%"><small><b>NOTA</b><small></th>
							<th class="text-center" width="10%"><small><b>ESTADO</b><small></th>
							<th class="text-center" width="10%"><small><b>F.EXPIRACIÓN</b><small></th>
							<th width="25%" class="text-center"><small><b>ACCIONES</b><small></th>
						</tr>
					</thead>';
		echo '<tbody>';
		$finales=0;
		$c=0;

		$pagina = (($page-1)*$per_page);

	    //$tipo_organismo = substr($_SESSION['organismo_codigo'],0,1);
	$badge = '';	
    foreach ($arr_datos as $row) {
        	$c++;
			$indice = $pagina + $c;
			$espacio = "&nbsp;";
			$accion_editar = '<a href="#" class="disabledbutton" onclick="cursadoEditar(\''.$idAlumno.'&'.$row['materia_id'].'&'.$row['materia_nombre'].'&'.$row['anio_cursado'].'&'.$row['cursado_id'].'&'.$row['nota'].'&'.$row['estado_final'].'&'.$row['fecha_vencimiento_regularidad'].'&'.$row['id'].'\')" title="Editar"><img src="../public/img/icons/edit_icon.png" width="20"></a>';
			$accion_eliminar = '<a href="#" onclick="cursadoEliminar('.$row['id'].')" title="Eliminar"><img src="../public/img/icons/delete_icon.png" width="17"></a>';
			$fecha_expiracion_materia = "------";
			if ($row['estado_final']=='Libre' || $row['estado_final']=='Regularizo') {
				$fecha_expiracion_materia = '<strong>'.substr($row['fecha_vencimiento_regularidad'],0,10).'</strong>';
				//die($fecha_expiracion_materia);
			};

			if ($row['estado_final']=='Aprobo' || $row['estado_final']=='Promociono') {
				$badge = 'badge-success';
			} else if ($row['estado_final']=='Regularizo') {
				$badge = 'badge-warning';
			} else if ($row['estado_final']=='Libre') {
				$badge = 'badge-danger';
			} else if ($row['estado_final']=='Cursando') {
				$badge = 'badge-info';
			}
			
        	echo '<tr>';
			echo '   <td align="center">'.'<b>'.$indice.'</b></td>'.
				 '   <td align="left"><small>'.$row['materia_nombre'].'&nbsp;<b>('.$row['materia_id'].')</b></small></td>'.
				 '   <td align="right"><small>'.$row['materia_anio'].'</small></td>'.
				 '   <td align="right"><small>'.$row['cursado_nombre'].' <strong>('.$row['cursado_id'].')</strong></small></td>'.
				 '   <td align="right"><small>'.$row['anio_cursado'].'</small></td>'.
				 '   <td align="right"><small>'.$row['nota'].'</small></td>'.
				 '   <td align="right"><small><span class="badge '.$badge.'">'.$row['estado_final'].'</span></small></td>'.
				 '   <td align="right"><small>'.$fecha_expiracion_materia.'</small></td>'.
				 '   <td align="left" class="text-center"><small>'.$accion_eliminar.'</small></td>';
			echo '</tr>';
        $finales++;
    }; // END FOREACH
	echo "   </tbody>
	         <tfoot>
			 </tfoot>
		 </table>";
} else {
	echo '<table class="table" id="tabla_materias_cursadas">
			<tbody>
				<tr><td><div class="alert alert-danger" role="alert">
							<b>Atenci&oacute;n:</b> No existen Materias Cursadas.
						</div></td></tr>
			</tbody>
			<tfoot>
			</tfoot>
	     </table>';
};
};

?>
