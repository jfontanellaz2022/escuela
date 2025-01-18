<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

//require_once 'seguridadNivel1.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
include_once 'ArrayHash.class.php';
require_once "_seguridad.php";

$rol_usuario = '';
//$rol_admin = ($_SESSION['user_rol']=='admin' || $_SESSION['user_rol']=='SYSTEM')?'':'disabledbutton';

$rol_admin = '';

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$busqueda = isset($_POST['busqueda_rapida'])?$_POST['busqueda_rapida']:false;
$idCarrera = isset($_POST['carrera_id'])?SanitizeVars::INT($_POST['carrera_id']):FALSE;
$clave = isset($_POST['clave'])?SanitizeVars::STRING($_POST['clave']):FALSE;
$hash = isset($_POST['hash'])?SanitizeVars::STRING($_POST['hash']):FALSE;

$nombre = isset($_POST['nombre'])?SanitizeVars::APELLIDONOMBRES($_POST['nombre']):false;
$dni = isset($_POST['dni'])?$_POST['dni']:false;
$anio = isset($_POST['anio'])?$_POST['anio']:false;
$mesa = isset($_POST['mesa'])?$_POST['mesa']:false;


$andX = array();
$orX = array();
$andX[] = "aec.idAlumno = a.id";
$sql = $sqlCantidadFilas = "";

if($action == 'listar' && $idCarrera && ArrayHash::check($hash, array($MY_SECRET=>$idCarrera))){
	$tables = " alumno_estudia_carrera aec, alumno a ";
	$campos = " aec.idAlumno, a.dni, a.apellido, a.nombre, habilitado, aec.anio as anio_ingreso_carrera, aec.mesa_especial, aec.fecha_inscripcion ";
	
	if ($nombre) $andX[] = '(a.apellido like "%' . $nombre . '%" or a.nombre like "%' . $nombre . '%")';  
	if ($dni) $andX[] = '(a.dni like "%' . $dni . '%")';
	if ($idCarrera)  $andX[] = " (aec.idCarrera = $idCarrera) ";
	if ($anio)  $andX[] = " (aec.anio = '$anio') ";
	if ($mesa)  $andX[] = " (aec.mesa_especial = '$mesa') ";

	if ($busqueda)  $andX[] = " (a.apellido like '%$busqueda%' or a.nombre like '%$busqueda%'  or a.dni like '%$busqueda%' or a.id like '%$busqueda%') ";
	if (count($andX)>0) $where = ' WHERE (' . implode(" and ",$andX) . ') ';
	else $where = '';
	$where = $where . " ORDER BY a.apellido asc, a.nombre asc ";
   
	if ($busqueda) 
	{
		$campos_nuevos = "  x.idAlumno, x.dni, x.apellido, x.nombre, x.habilitado, x.anio_ingreso_carrera, x.mesa_especial, x.fecha_inscripcion ";
		$subConsultaFiltros = "SELECT $campos FROM  $tables $where";
		//die($subConsultaFiltros);
		$sqlCantidadFilas =  "SELECT count(*) AS numrows FROM  ($subConsultaFiltros) x
							  WHERE (x.apellido like '%$busqueda%' or 
									 x.nombre like '%$busqueda%' or 
									 x.dni like '%$busqueda%')";
		$sqlFinal =  "SELECT $campos_nuevos FROM  ($subConsultaFiltros) x 
		              WHERE (x.apellido like '%$busqueda%' or 
							 x.nombre like '%$busqueda%' or 
							 x.dni like '%$busqueda%')";
		//die($sqlFinal);
	} else {
		$sqlCantidadFilas = "SELECT count(*) AS numrows FROM $tables $where "; 
		$sqlFinal = "SELECT $campos FROM  $tables $where";
		//die("".$sqlFinal);
	}

	//PAGINATION VARIABLES
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:10; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
    $sql = "SELECT count(*) AS numrows FROM $tables $where "; 
	$count_query = mysqli_query($conex,$sql);
	if ($row = mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$sql_2 = "SELECT $campos FROM  $tables $where LIMIT $offset,$per_page";
	//die($sql_2);
	$query = mysqli_query($conex,$sql_2);

	//***************************************************************************************************
	//****************  PONER LOS NOMBRES DE LOS CAMPOS *************************************************
	//***************************************************************************************************
	$campo1 = "Id";$campo2 = "Nombres"; $campo3 = "Dni"; $campo4 = "Año"; $campo5 = "Mesa"; 
	//***************************************************************************************************
	//***************************************************************************************************
    echo '<div class="table-responsive" ">
				<table class="table table-striped table-bordered table-hover" id="tabla_calendario">
					<thead>
						<tr>
							<th class="text-left" colspan="13">
							   <table width="100%">
							 	   <tr>
										<th class="text-left" colspan="5">
											<button class="btn btn-primary '.$rol_admin.'" onclick="entidadCrear()">Agregar</button>&nbsp;
											<button class="btn btn-primary '.$rol_admin.'" onclick="entidadEliminarSeleccionados()">Borrar Seleccionados</button>&nbsp;
										</th>
										<th class="text-right" colspan="7">
												<div class="col-7">
												<div class="input-group">
													<input id="inputBusquedaRapida" placeholder="Busqueda Rapida" type="text" class="form-control" value="'.$busqueda.'"> 
													<div class="input-group-append">
													<div class="input-group-text">
														<a href="#" onclick="aplicarBusquedaRapida()"><i class="fa fa-search"></i></a>
													</div>
													</div>
												</div>
												</div>
									    </th>
									</tr>  
							   </table>
							</th>
        				</tr>
						<tr>
							<th class="text-center" width="5%"><small><b><input type="checkbox" class="'.$rol_admin.'" id="seleccionar_todos"></b></small></th>
							<th width="5%" class="text-center text-primary" colspan=3><small><b>ACCIONES</b><small></th>
							<th class="text-center text-primary" width="40%"><small><b>'.strtoupper($campo2).'</b></small></th>
							<th class="text-center text-primary" width="10%"><small><b>'.strtoupper($campo3).'</b></small></th>
							<th class="text-center text-primary" width="10%"><small><b>AÑO</b></small></th>
							<th class="text-center text-primary" width="10%"><small><b>'.strtoupper($campo5).'</b></small></th>
						</tr>';
		//-- FILTROS --
		echo '<tr>
							<th class="text-right" colspan=4>
							        <button class="btn btn-primary btn-sm" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../public/img/icons/filterminus.png" width="22"></button>
									<button class="btn btn-primary btn-sm" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../public/img/icons/filter.png" width="22"></button>
						    </th>
							<th class="text-center"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo2.'" value="'.$nombre.'" autocomplete="off"></b></small></th>
							<th class="text-center"><small><b><input type="text" class="form-control" id="inputFiltro'.$campo3.'" value="'.$dni.'" autocomplete="off"></b></small></th>
							<th class="text-center"><small><b><input type="text" class="form-control" id="inputFiltro'.'Anio'.'" value="'.$anio.'" autocomplete="off"></b></small></th>
							<th class="text-center"><small><select class="form-control" id="inputFiltro'.$campo5.'"><option value="" '.(($mesa=="")?'selected':'').'>Todos</option><option value="Si" '.(($mesa=="Si")?'selected':'').'>Si</option><option value="No" '.(($mesa=="No")?'selected':'').'>No</option></select></small></th>
						</tr>';
		echo '</thead>';

		if ($numrows>0){
		$finales=0;
		$c=0;
		$pagina = (($page-1)*$per_page);

	    //$tipo_organismo = substr($_SESSION['organismo_codigo'],0,1);
		while ($row=mysqli_fetch_assoc($query)) {
						$alumno_id_hash = $hash = ArrayHash::encode(array($MY_SECRET=>$row['idAlumno']));
						$rowIdCampo1 = $row['idAlumno'];
						$rowCampo2 = $row['apellido'].', '.$row['nombre'];
						$rowCampo3 = $row['dni'];
						$rowCampo4 = $row['anio_ingreso_carrera'];
						$rowCampo5 = $row['mesa_especial'];
						echo '<tr>';
						echo '   <td align="center"><small><b><input type="checkbox" class="'.$rol_admin.' check" id="check_'.$rowIdCampo1.'" name="check_usu[]" value="'.$rowIdCampo1.'"></b></small></td>'.
							 '   <td align="center" colspan="3">
							 		<div class="btn-group pull-right" role="group">
										<button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Acciones
										</button>
										<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
											<a class="'.$rol_usuario.'dropdown-item small disabled" href="#" onclick="entidadVer('.$rowIdCampo1.')"><i class="fa fa-address-card-o"></i>&nbsp;Ver</a>
											<a class="'.$rol_usuario.' dropdown-item small disabled" href="#" onclick="entidadEditar('.$rowIdCampo1.')"><i class="fa fa-edit"></i>&nbsp;Editar</a>
											<a class="'.$rol_admin.' dropdown-item small" href="menuCarreraMateriasCursadasPorAlumno.php?alumno_id='.$row['idAlumno'].'&carrera_id='.$idCarrera.'&hash='.$alumno_id_hash.'"><i class="fa fa-address-card-o"></i>&nbsp;M.Cursadas</a>
											<a class="'.$rol_admin.' dropdown-item small" href="menuCarreraMateriasRendidasPorAlumno.php?alumno_id='.$row['idAlumno'].'&carrera_id='.$idCarrera.'&hash='.$alumno_id_hash.'"><i class="fa fa-address-card-o"></i>&nbsp;M.Rendidas</a>
										</div>
                             		</div>
							 </td>'.
							 '   <td align="left"><small>'.$rowCampo2.'&nbsp;<strong>('.$rowIdCampo1.')</strong><small></td>'.
							 '   <td align="center"><small>'.$rowCampo3.'</small></td>'.
							 '   <td align="center"><small>'.$rowCampo4.'</small></td>'.
							 '   <td align="center"><small>'.$rowCampo5.'</small></td>';
						echo '</tr>';
						$finales++;
		};
		echo "</tbody><tfoot><tr><td colspan='13'>";
			$inicios=$offset+1;
			$finales+=$inicios-1;
			echo "<br>";
			echo "Mostrando <strong>$inicios</strong> al <strong>$finales</strong> de <strong>$numrows</strong> registros";
			echo "<br><p>";
			echo paginate($page, $total_pages, $adjacents);
			echo "</td></tr>";
			echo '</tfoot>';
			echo '</table>';
}  else {
	echo '<tbody>';
	echo '<tr><td colspan="13">
	              <div class="alert alert-exclamation" role="alert">
				        <span style="color: #000000;">
				            <i class="fa fa-info-circle" aria-hidden="true"></i>
						    &nbsp;<strong>Atención:</strong> No existen Resultados.
					    </span>
			       </div>
			  </td></tr>';
	echo '</tbody>';
	echo '</table>';
};
};

?>
