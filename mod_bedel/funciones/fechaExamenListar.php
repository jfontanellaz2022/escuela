<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

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

/*$codigo = isset($_POST['codigo'])?$_POST['codigo']:"";
$descripcion = isset($_POST['descripcion'])?SanitizeVars::STRING($_POST['descripcion']):false;
$habilitada = isset($_POST['habilitada'])?SanitizeVars::STRING($_POST['habilitada']):false;*/

//die($id.'-'.$anioLectivo.'-'.$evento.'-'.$fechaInicio.'-'.$fechaFinalizacion);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$andX = array();
$andX[] = " (mf.idCalendarioAcademico=c.id) ";
$andX[] = " (c.idEvento = e.id) ";
$andX[] = " (mf.idMateria=m.id) ";

$sql = $sqlCantidadFilas = "";

if($action == 'listar'){
	//die("entroooo");

	$campos = " mf.id as id_fecha_examen, mf.fechaExamen as fecha_examen, c.id as id_calendario, c.AnioLectivo as anio_lectivo, 
	            e.descripcion as descripcion_evento, mf.idCalendarioAcademico, mf.llamado, m.id as id_materia, 
	            m.nombre as nombre_materia, m.carrera";
	$tables = " materia_tiene_fechaexamen mf,materia m, calendarioacademico c, evento e ";
	$where = "  ";
	
	
	if ($busqueda) $andX[] = " ((m.nombre like '%" . $busqueda ."%') or (m.id='$busqueda'))";
	

	if (count($andX)>0) $where = ' WHERE ' . implode(" and ",$andX) . ' ';
	else $where = '';
    
	$where = $where . " ORDER BY mf.id DESC"; 
    
	$sql = "";

	
	$sqlCantidadFilas = "SELECT count(*) AS numrows
						 FROM $tables $where";
	
	$sqlFinal = "SELECT $campos FROM  $tables $where";
	
	
	//PAGINATION VARIABLES
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
   
	$count_query = mysqli_query($conex,$sqlCantidadFilas);
	
	if ($row = mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$sqlFinal .=  " LIMIT $offset,$per_page ";
	$query = mysqli_query($conex,$sqlFinal);

	

	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$labelCampo1 = "ID";$labelCampo2 = "NOMBRE"; $labelCampo3 = "CARRERA"; $labelCampo4 = "TURNO"; $labelCampo5 = "LLAMADO"; $labelCampo6 = "FECHA EXAMEN";
	$campo1 = "Id";$campo2 = "Nombre"; $campo3 = "Carrera"; $campo4 = "Turno"; $campo5 = "Llamado"; $campo6 = "Fecha Examen";
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
                    <button class="btn btn-primary rol_admin" onclick="entidadEliminarSeleccionados()" disabled>Borrar Seleccionados</button>&nbsp;
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
            <th class="text-center text-primary" width="25%"><small><b><?=$labelCampo2?></b></small></th>
            <th class="text-center text-primary" width="15%"><small><b><?=$labelCampo3?></b></small></th>
			<th class="text-center text-primary" width="25%"><small><b><?=$labelCampo4?></b></small></th>
			<th class="text-center text-primary" width="5%"><small><b><?=$labelCampo5?></b></small></th>
			<th class="text-center text-primary" width="10%"><small><b><?=$labelCampo6?></b></small></th>

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
				$rowIdCampo1 = $row['id_fecha_examen']; 
				$hashId = ArrayHash::encode(array($MY_SECRET=>$rowIdCampo1));
				$rowCampo2 = $row['nombre_materia'] . ' <strong>('.$row['id_materia'] . ')</strong>';
				$rowCampo3 = $row['carrera'];
				$rowCampo4 = $row['descripcion_evento'] . ' del año ' .  $row['anio_lectivo'] . ' <strong>(' . $row['id_calendario'] . ')</strong>';
				$rowCampo5 = $row['llamado'];
				$rowCampo6 = $row['fecha_examen'];

?>						
       
            <tr>
                  <td align="center"><small><b><input type="checkbox" class=" check" id="check_<?=$rowIdCampo1?>" name="check_usu[]" value="<?=$rowIdCampo1?>"></b></small></td>
                      <td align="center" colspan="3">
                      <div class="btn-group pull-right" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          <a class=" dropdown-item small" href="#" onclick="entidadDuplicar(<?=$rowIdCampo1?>)"><i class="fa fa-address-card-o"></i>&nbsp;Duplicar</a>
                          <a class=" dropdown-item small" href="#" onclick="entidadEditar('<?=$rowIdCampo1?>')"><i class="fa fa-edit"></i>&nbsp;Editar Fecha</a>
                          <a class=" dropdown-item small" href="#" onclick="entidadEliminar('<?=$rowIdCampo1?>')"><i class="fa fa-trash"></i>&nbsp;Borrar</a>
                        </div>
                                    </div>
                  </td>
				  <td align="center"><small><?=$rowIdCampo1;?><small></td>
                  <td align="left"><small><?=$rowCampo2;?><small></td>
                  <td align="left"><small><?=$rowCampo3;?></small></td>
                  <td align="center"><small><?=$rowCampo4;?></small></td>
				  <td align="center"><small><?=$rowCampo5;?></small></td>
				  <td align="center"><small><?=$rowCampo6;?></small></td>
                  
              </tr>
		<?php 
				$finales++;
			};
		?>	  
        </tbody>
         <tfoot>
             <tr>
				<td colspan='10'>
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
				<tr><td colspan="10">
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
