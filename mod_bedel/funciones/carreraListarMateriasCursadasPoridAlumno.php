<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
include_once 'ArrayHash.class.php';
require_once "_seguridad.php";

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$idCarrera = isset($_POST['carrera_id'])?SanitizeVars::INT($_POST['carrera_id']):FALSE;
$idAlumno = isset($_POST['alumno_id'])?SanitizeVars::INT($_POST['alumno_id']):FALSE;
$hash = isset($_POST['hash'])?SanitizeVars::STRING($_POST['hash']):FALSE;

//die($idCarrera.'*'.$hash);

$sWhere = "";
$where = array();
$where[] = " acm.idAlumno = $idAlumno ";
$where[] = " acm.idMateria IN (SELECT idMateria FROM carrera_tiene_materia WHERE idCarrera = $idCarrera) ";
$where[] = " acm.idMateria = m.id ";
$where[] = " acm.idTipoCursadoAlumno = tca.id ";

if($action == 'listar' && $idCarrera /* && ArrayHash::check($hash, array($MY_SECRET=>$idAlumno))*/){
	$tables = " alumno_cursa_materia acm, materia m, tipo_cursado_alumno tca ";
	$campos = " acm.id, m.id as 'materia_id', m.nombre, m.anio, acm.idTipoCursadoAlumno, acm.anioCursado, acm.nota, acm.estado_final, acm.FechaVencimientoRegularidad, tca.nombre as tipo_cursado ";
		
	if (count($where)>0) $sWhere =' WHERE ' . implode(" and ",$where);
	
	$sWhere .= " ORDER BY m.anio DESC, m.nombre ASC  ";
    
	//PAGINATION VARIABLES
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:10; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
    $sql = "SELECT count(*) AS numrows FROM $tables $sWhere "; 
	$count_query = mysqli_query($conex,$sql);
	if ($row = mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$sql_2 = "SELECT $campos FROM  $tables $sWhere LIMIT $offset,$per_page";
	//die($sql_2);
	$query = mysqli_query($conex,$sql_2);
	//loop through fetched data
	if ($numrows>0){
		$c=0;
			echo '<div class="table-responsive" ">
				<table class="table table-striped table-bordered table-hover" id="tabla_calendario">
					<thead class="thead-dark">
						<tr>
							<th class="text-center" width="5%">#</th>
							<th class="text-center" width="40%"><small><b>MATERIAS</b><small></th>
							<th class="text-center" width="10%"><small><b>AÑO</b><small></th>
							<th class="text-center" width="10%"><small><b>T.CURSADO</b><small></th>
							<th class="text-center" width="10%"><small><b>AÑO CURSO</b><small></th>
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
    while ($row = mysqli_fetch_assoc($query)) {
        	$c++;
			$indice = $pagina + $c;
			//$alumno_id_hash = $hash = ArrayHash::encode(array($MY_SECRET=>$row['id']));
			$espacio = "&nbsp;";
			$accion_editar = '<a href="#" onclick="cursadoEditar(\''.$idAlumno.'&'.$row['materia_id'].'&'.$row['nombre'].'&'.$row['anioCursado'].'&'.$row['idTipoCursadoAlumno'].'&'.$row['nota'].'&'.$row['estado_final'].'&'.$row['FechaVencimientoRegularidad'].'&'.$row['id'].'\')" title="Editar"><img src="../public/img/icons/edit_icon.png" width="20"></a>';
			$accion_eliminar = '<a href="#" onclick="cursadoEliminar('.$row['id'].')" title="Eliminar"><img src="../public/img/icons/delete_icon.png" width="17"></a>';
			$fecha_expiracion_materia = "------";
			if ($row['estado_final']=='Libre' || $row['estado_final']=='Regularizo') {
				$fecha_expiracion_materia = '<strong>'.substr($row['FechaVencimientoRegularidad'],0,10).'</strong>';
				//die($fecha_expiracion_materia);
			};

			
			/*$hash = ArrayHash::encode(array($secreto=>$row['id']));*/
        	echo '<tr>';
			echo '   <td align="center">'.'<b>'.$indice.'</b></td>'.
				 '   <td align="left"><small>'.$row['nombre'].'&nbsp;<b>('.$row['id'].')</b></small></td>'.
				 '   <td align="right"><small>'.$row['anio'].'</small></td>'.
				 '   <td align="right"><small>'.$row['tipo_cursado'].'</small></td>'.
				 '   <td align="right"><small>'.$row['anioCursado'].'</small></td>'.
				 '   <td align="right"><small>'.$row['nota'].'</small></td>'.
				 '   <td align="right"><small>'.$row['estado_final'].'</small></td>'.
				 '   <td align="right"><small>'.$fecha_expiracion_materia.'</small></td>'.
				 '   <td align="left" class="text-center"><small>'.$accion_eliminar.$espacio.$accion_editar.$espacio.'</small></td>';
			echo '</tr>';
        $finales++;
    };
		echo "</tbody><tfoot><tr><td colspan='9'>";
		$inicios=$offset+1;
		$finales+=$inicios-1;
		echo "<br>";
		echo "Mostrando <strong>$inicios</strong> al <strong>$finales</strong> de <strong>$numrows</strong> registros";
		echo "<br><p>";
		echo paginate($page, $total_pages, $adjacents);
		echo "</td></tr>";
    echo '</tfoot>';
	echo '</table>';
} else {
	echo '<table class="table">';
	echo '<tbody>';
	echo '<tr><td><div class="alert alert-danger" role="alert">
				 <b>Atenci&oacute;n:</b> No existen Eventos en el calendario.
			 </div></td></tr>';
	echo '</tbody>';
	echo '</table>';
};
};

?>
