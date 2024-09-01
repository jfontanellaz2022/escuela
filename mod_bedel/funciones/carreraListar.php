<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

//require_once 'controlAcceso.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
include_once 'ArrayHash.class.php';
require_once "_seguridad.php";

//die(unserialize('a:1:{i:0;s:8:"empleado";}')[0]);

$rol_usuario = '';
//$rol_admin = ($_SESSION['user_rol']=='admin' || $_SESSION['user_rol']=='SYSTEM')?'':'disabledbutton';

$rol_admin = '';


/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$busqueda = isset($_POST['busqueda_rapida'])?$_POST['busqueda_rapida']:null;

$codigo = isset($_POST['codigo'])?$_POST['codigo']:"";
$descripcion = isset($_POST['descripcion'])?SanitizeVars::STRING($_POST['descripcion']):false;
$habilitada = isset($_POST['habilitada'])?SanitizeVars::STRING($_POST['habilitada']):false;

//die($id.'-'.$anioLectivo.'-'.$evento.'-'.$fechaInicio.'-'.$fechaFinalizacion);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$andX = array();



$sql = $sqlCantidadFilas = "";

if($action == 'listar'){
	$tables = " carrera c";
	$campos = " id, codigo, descripcion, habilitada";
	//if ($codigo) $andX[] = 'e.codigo = ' . $codigo;
	if ($codigo) $andX[] = 'c.codigo = ' . $codigo;

	if ($descripcion) $andX[] = "c.descripcion like '%" . $descripcion ."%'";
	if ($habilitada) $andX[] = "c.habilitada ='$habilitada'";


	if (count($andX)>0) $where = ' WHERE (' . implode(" and ",$andX) . ') ';
	else $where = '';
    
	$where = $where . " ORDER BY c.id DESC";
    $sql = "";

	if ($busqueda) 
	{

		$campos_nuevos = "  x.id, x.codigo, x.descripcion, x.habilitada ";
		$subConsultaFiltros = "SELECT $campos FROM  $tables $where";
		//die($subConsultaFiltros);
		$sqlCantidadFilas =  "SELECT count(*) AS numrows FROM  ($subConsultaFiltros) x
							  WHERE (x.descripcion like '%$busqueda%' or x.codigo='$busqueda')";
		$sqlFinal =  "SELECT $campos_nuevos FROM  ($subConsultaFiltros) x 
		              WHERE (x.descripcion like '%$busqueda%' or x.codigo='$busqueda')";
		// die($sqlFinal);

	} else {
		$sqlCantidadFilas = "SELECT count(*) AS numrows FROM $tables $where "; 
		$sqlFinal = "SELECT $campos FROM  $tables $where";
		//  die("".$sqlFinal);
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
	$labelCampo1 = "ID";$labelCampo2 = "CODIGO"; $labelCampo3 = "DESCRIPCION"; $labelCampo4 = "HABILITADa";
	$campo1 = "Id";$campo2 = "Codigo"; $campo3 = "Descripcion"; $campo4 = "Habilitada";
	//*********************************************************** */
	//*****
?>	

<div class="table-responsive" >
      <table class="table table-striped table-bordered table-hover bg2">
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
            <th class="text-center text-primary" width="8%"><small><b><?=$labelCampo2?></b></small></th>
            <th class="text-center text-primary" width="35%"><small><b><?=$labelCampo3?></b></small></th>
            <th class="text-center text-primary" width="12%"><small><b><?=$labelCampo4?></b></small></th>

          </tr>
          <tr>
            <th class="text-right" colspan=4>
                <button class="btn btn-primary btn-sm" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../public/img/icons/filterminus.png" width="22"></button>
                <button class="btn btn-primary btn-sm" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../public/img/icons/filter.png" width="22"></button>
              </th>


			<th class="text-center" width="8%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo2?>" value="<?=$codigo?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo3?>" value="<?=$descripcion?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo4?>" value="<?=$habilitada?>"></b></small></th>

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
				$hashId = ArrayHash::encode(array($MY_SECRET=>$rowIdCampo1));
				$rowCampo2 = $row['codigo'];
				$rowCampo3 = $row['descripcion'];
				$rowCampo4 = $row['habilitada'];

?>						
       
            <tr>
                  <td align="center"><small><b><input type="checkbox" class=" check" id="check_<?=$rowIdCampo1?>" name="check_usu[]" value="<?=$rowIdCampo1?>"></b></small></td>
                      <td align="center" colspan="3">
                      <div class="btn-group pull-right" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          <a class=" dropdown-item small" href="menuCarreraAlumnos.php?id=<?=$rowIdCampo1?>&hash=<?=$hashId?>" onclick=""><i class="fa fa-address-card-o"></i>&nbsp;Alumnos</a>
                          <a class=" dropdown-item small" href="#" onclick="entidadEditar('<?=$rowIdCampo1?>')"><i class="fa fa-edit"></i>&nbsp;Editar</a>
                          <a class=" dropdown-item small" href="#" data-toggle="modal" data-target="#confirmarModal" data-id="<?=$rowIdCampo1?>"><i class="fa fa-trash"></i>&nbsp;Borrar</a>
                          <a class=" dropdown-item small disabledbutton" href="#" onclick="enviarEmail('<?=$rowIdCampo1?>')"><i class="fa fa-envelope"></i>&nbsp;Enviar Email</a>
                        </div>
                                    </div>
                  </td>

                  <td align="center"><small><?=$rowCampo2;?><small></td>
                  <td align="left"><small><?=$rowCampo3;?></small></td>
                  <td align="center"><small><?=$rowCampo4;?></small></td>

                  
              </tr>
		<?php 
				$finales++;
			};
		?>	  
        </tbody>
         <tfoot>
             <tr>
				<td colspan='7'>
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
				<tr><td colspan="5">
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
