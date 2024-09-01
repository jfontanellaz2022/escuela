<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
require_once "_seguridad.php";

//die(unserialize('a:1:{i:0;s:8:"empleado";}')[0]);

$rol_usuario = '';
//$rol_admin = ($_SESSION['user_rol']=='admin' || $_SESSION['user_rol']=='SYSTEM')?'':'disabledbutton';

$rol_admin = '';


/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$busqueda = isset($_POST['busqueda_rapida'])?$_POST['busqueda_rapida']:false;

$id = isset($_POST['id'])?$_POST['id']:"";
$anioLectivo = isset($_POST['anio']) ? $_POST['anio']:"";
$evento = isset($_POST['evento'])?SanitizeVars::STRING($_POST['evento']):false;
$fechaInicio = isset($_POST['fechaInicio'])?SanitizeVars::DATE($_POST['fechaInicio']):false;
$fechaFinalizacion = isset($_POST['fechaFinalizacion'])?SanitizeVars::DATE($_POST['fechaFinalizacion']):false;

//die($id.'-'.$anioLectivo.'-'.$evento.'-'.$fechaInicio.'-'.$fechaFinalizacion);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$andX = array();
$orX = array();

$andX[] = " c.idEvento = e.id ";

$sql = $sqlCantidadFilas = "";

if($action == 'listar'){
	$tables = " calendarioacademico c, evento e ";
	$campos = " c.id, c.AnioLectivo, e.descripcion, c.fechaInicioEvento, c.fechaFinalEvento, e.codigo ";
	//if ($codigo) $andX[] = 'e.codigo = ' . $codigo;
	if ($id) $andX[] = 'c.id = ' . $id;
	if ($anioLectivo) $andX[] = 'c.AnioLectivo = ' . $anioLectivo;
	if ($evento) $andX[] = "(e.descripcion like '%" . $evento ."%' or e.codigo='$evento')";
	if ($fechaInicio) $andX[] = "c.fechaInicioEvento like '" . $fechaInicio . "'";
	if ($fechaFinalizacion) $andX[] = "c.fechaFinalEvento like '" . $fechaFinalizacion . "'";

	if (count($andX)>0) $where = ' WHERE (' . implode(" and ",$andX) . ') ';
	else $where = '';
    
	$where = $where . " ORDER BY c.fechaInicioEvento DESC ";
    $sql = "";

	if ($busqueda) 
	{
		$campos_nuevos = "  x.id, x.AnioLectivo, x.descripcion, x.fechaInicioEvento, x.fechaFinalEvento, x.codigo ";
		$subConsultaFiltros = "SELECT $campos FROM  $tables $where";
		//die($subConsultaFiltros);
		$sqlCantidadFilas =  "SELECT count(*) AS numrows FROM  ($subConsultaFiltros) x
							  WHERE (x.descripcion like '%$busqueda%' or x.codigo='$busqueda')";
		$sqlFinal =  "SELECT $campos_nuevos FROM  ($subConsultaFiltros) x 
		              WHERE (x.descripcion like '%$busqueda%' or x.codigo=$busqueda)";
		//die($sqlFinal);
	} else {
		$sqlCantidadFilas = "SELECT count(*) AS numrows FROM $tables $where "; 
		$sqlFinal = "SELECT $campos FROM  $tables $where";
		//die("".$sqlFinal);
	}


	//PAGINATION VARIABLES
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
   
	//die($sqlCantidadFilas);
	$count_query = mysqli_query($conex,$sqlCantidadFilas);
	if ($row = mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$sqlFinal .=  " LIMIT $offset,$per_page ";
	//die($sqlFinal);
	$query = mysqli_query($conex,$sqlFinal);

	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$labelCampo1 = "ID";$labelCampo2 = "AÑO"; $labelCampo3 = "EVENTO"; $labelCampo4 = "F.INICIO"; $labelCampo5 = "F.FINALIZACION";
	$campo1 = "Id";$campo2 = "Anio"; $campo3 = "Evento"; $campo4 = "FechaInicio"; $campo5 = "FechaFinalizacion";
	//*********************************************************** */
	//*****
?>	

<div class="table-responsive" >
      <table class="table table-striped table-bordered table-hover bg2" id="tabla_calendario">
        <thead>
          <tr>
            <th class="text-left" colspan="13">
               <table class="table borderless" width="100%">
                  <tr>
                  <th class="text-left" colspan="5">
                    <button class="btn btn-primary rol_admin" onclick="entidadCrear()">Agregar</button>&nbsp;
                    <button class="btn btn-primary rol_admin" onclick="entidadEliminarSeleccionados()">Borrar Seleccionados</button>&nbsp;
                  </th>
                  <th class="text-right" colspan="7">
                      <div class="col-7">
                      <div class="input-group">
                        <input id="inputBusquedaRapida" placeholder="Busqueda Rapida" type="text" class="form-control" value="<?=$busqueda?>"> 
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
            <th class="text-center" width="5%"><small><b><input type="checkbox" class="" id="seleccionar_todos"></b></small></th>
            <th width="5%" class="text-center text-primary" colspan=3><small><b>ACCIONES</b><small></th>
			<th class="text-center text-primary" width="5%"><small><b><?=$labelCampo1?></b></small></th>
            <th class="text-center text-primary" width="8%"><small><b><?=$labelCampo2?></b></small></th>
            <th class="text-center text-primary" width="35%"><small><b><?=$labelCampo3?></b></small></th>
            <th class="text-center text-primary" width="12%"><small><b><?=$labelCampo4?></b></small></th>
            <th class="text-center text-primary" width="12%"><small><b><?=$labelCampo5?></b></small></th>
          </tr>
          <tr>
            <th class="text-right" colspan=4>
                <button class="btn btn-primary btn-sm" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../public/img/icons/filterminus.png" width="22"></button>
                <button class="btn btn-primary btn-sm" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../public/img/icons/filter.png" width="22"></button>
              </th>
            <th class="text-center" width="7%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo1?>" value="<?=$id?>"></b></small></th>
			<th class="text-center" width="8%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo2?>" value="<?=$anioLectivo?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo3?>" value="<?=$evento?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo4?>" value="<?=$fechaInicio?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo5?>" value="<?=$fechaFinalizacion?>"></b></small></th>
          </tr>
        </thead>
		<tbody>
<?php
if ($numrows>0){
	$finales = $c = 0;
	$pagina = (($page-1)*$per_page);
	while ($row=mysqli_fetch_assoc($query)) {
				$c++;
				$indice = $pagina + $c;
				$rowIdCampo1 = $row['id'];
				$rowCampo2 = $row['AnioLectivo'];
				$rowCampo3 = $row['descripcion'].' ('.$row['codigo'].')';
				$rowCampo4 = $row['fechaInicioEvento'];
				$rowCampo5 = $row['fechaFinalEvento'];
?>						
       
            <tr>
                  <td align="center"><small><b><input type="checkbox" class=" check" id="check_<?=$rowIdCampo1?>" name="check_usu[]" value="<?=$rowIdCampo1?>"></b></small></td>
                      <td align="center" colspan="3">
                      <div class="btn-group pull-right" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          <a class=" dropdown-item small" href="#" onclick="entidadVer('<?=$rowIdCampo1?>')"><i class="fa fa-address-card-o"></i>&nbsp;Ver</a>
                          <a class=" dropdown-item small" href="#" onclick="entidadEditar('<?=$rowIdCampo1?>')"><i class="fa fa-edit"></i>&nbsp;Editar</a>
                          <a class=" dropdown-item small" href="#" data-toggle="modal" data-target="#confirmarModal" data-id="<?=$rowIdCampo1?>"><i class="fa fa-trash"></i>&nbsp;Borrar</a>
                          <a class=" dropdown-item small disabledbutton" href="#" onclick="enviarEmail('<?=$rowIdCampo1?>')"><i class="fa fa-envelope"></i>&nbsp;Enviar Email</a>
                        </div>
                                    </div>
                  </td>
				  <td align="center"><small><?=$rowIdCampo1;?><small></td>
                  <td align="center"><small><?=$rowCampo2;?><small></td>
                  <td align="left"><small><?=$rowCampo3;?></small></td>
                  <td align="center"><small><?=$rowCampo4;?></small></td>
				  <td align="center"><small><?=$rowCampo5;?></small></td>
                  
              </tr>
		<?php 
				$finales++;
			};
		?>	  
        </tbody>
         <tfoot>
             <tr>
				<td colspan='9'>
					<?php
						$inicios=$offset+1;
						$finales+=$inicios-1;
						echo "<br>";
						echo "Mostrando <strong>$inicios</strong> al <strong>$finales</strong> de <strong>$numrows</strong> registros";
						echo "<br><p>";
						echo paginate($page, $total_pages, $adjacents);
					?>
			    </td>
			</tr>
        </tfoot>
        </table>
		<?php
			} else {
		?>
				<tbody>
				<tr><td colspan="9">
							  <div class="alert alert-exclamation" role="alert">
									<span style="color: #000000;">
										<i class="fa fa-info-circle" aria-hidden="true"></i>
										&nbsp;<strong>Atención:</strong> No existen Resultados.
									</span>
							   </div>
						  </td></tr>
				</tbody>
				</table>
		<?php		
			};
		};
		?>
    </div>
