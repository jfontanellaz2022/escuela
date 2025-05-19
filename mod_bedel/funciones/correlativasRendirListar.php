<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'pagination.php';
require_once 'ArrayHash.class.php';
require_once 'CorrelativasParaRendirFilter.php';


$rol_usuario = '';
//$rol_admin = ($_SESSION['user_rol']=='admin' || $_SESSION['user_rol']=='SYSTEM')?'':'disabledbutton';

$rol_admin = '';

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$carrera = isset($_POST['carrera'])?SanitizeVars::STRING($_POST['carrera']):false;
$materia = isset($_POST['materia'])?SanitizeVars::STRING($_POST['materia']):false;
$materia_requerida = isset($_POST['materia_requerida'])?SanitizeVars::STRING($_POST['materia_requerida']):false;
$condicion = isset($_POST['condicion'])?SanitizeVars::INT($_POST['condicion']):0;

$sql = $sqlCantidadFilas = "";
$numrows = $total_pages = 0;
$arr_objetos = $arr_filtro = [];

if ($carrera) {
	$arr_filtro['carrera'] = $carrera;
}
if ($materia) {
	$arr_filtro['materia'] = $materia;
}
if ($materia_requerida) {
	$arr_filtro['materia_requerida'] = $materia_requerida;
}
if ($condicion) {
	$arr_filtro['condicion'] = $condicion;
}

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
if($action == 'listar'){
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	$objeto = new CorrelativasParaRendirFilter();
	$arr_objetos = $objeto->getCorrelativasRendirDetalle($page,$per_page,$arr_filtro);
	$numrows = $objeto->getCantidad();

	//PAGINATION VARIABLES
	$total_pages = ceil($numrows/$per_page);
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	
	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$labelCampo1 = "ID";$labelCampo2 = "CARRERA";$labelCampo3 = "MATERIA"; $labelCampo4 = "MATERIA REQUERIDA"; $labelCampo5 = "CONDICION";
	//*********************************************************** */
?>	

<div class="table-responsive" >
      <table class="table table-striped table-bordered table-hover bg2">
        <thead>
          <tr>
            <th class="text-left" colspan="8">
               <table class="table borderless" width="100%">
                  <tr>
                  <th class="text-left" colspan="6">
                    <button class="btn btn-primary rol_admin" onclick="entidadCrear()">Agregar</button>&nbsp;
                    <button class="btn btn-primary rol_admin" onclick="entidadEliminarSeleccionados()">Borrar Seleccionados</button>&nbsp;
                  </th>
                  
                </tr>  
               </table>
            </th>
          </tr>
          <tr>
            <th class="text-center" width="5%"><small><b><input type="checkbox" class="" id="seleccionar_todos"></b></small></th>
            <th width="5%" class="text-center text-primary" colspan=3><small><b>ACCIONES</b><small></th>
            <th class="text-center text-primary" width="15%"><small><b><?=$labelCampo2?></b></small></th>
            <th class="text-center text-primary" width="32%"><small><b><?=$labelCampo3?><b></small></th>
			<th class="text-center text-primary" width="32%"><small><b><?=$labelCampo4?><b></small></th>
			<th class="text-center text-primary" width="14%"><small><b><?=$labelCampo5?><b></small></th>
          </tr>
		  <tr>
            <th class="text-right" colspan=4>
                <button class="btn btn-primary btn-sm" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../public/img/icons/filterminus.png" width="22"></button>
                <button class="btn btn-primary btn-sm" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../public/img/icons/filter.png" width="22"></button>
              </th>
            <th class="text-center"><small><b><input type="text" class="form-control" id="inputFiltroCarrera" value="<?=$carrera?>"></b></small></th>
            <th class="text-center"><small><b><input type="text" class="form-control" id="inputFiltroMateria" value="<?=$materia?>"></b></small></th>
            <th class="text-center"><small><b><input type="text" class="form-control" id="inputFiltroMateriaRequerida" value="<?=$materia_requerida?>"></b></small></th>
			<th class="text-center"><select class="form-control" id="inputFiltroMateriaCondicion"><option value="0" <?=($condicion==0)?'selected':''?>>Todas</option><option value="1" <?=($condicion==1)?'selected':''?>>Regular</option><option value="2" <?=($condicion==2)?'selected':''?>>Aprobada</option></select></th>
          </tr>
        </thead>
		<tbody>
<?php
if (!empty($arr_objetos)){
	$finales = $c = 0;
	foreach ($arr_objetos as $fila) {
				$c++;
				//$indice = $pagina + $c;
				$rowIdCampo1 = $fila['id']; 
				//$hashId = ArrayHash::encode(array($MY_SECRET=>$rowIdCampo1));
				$rowCampo2 = $fila['carrera'];
				$rowCampo3 = $fila['materia_nombre'].' <strong>('.$fila['materia_id'].') Año:</strong> ' . $fila['materia_anio'];
				$rowCampo4 = $fila['materia_requerida_nombre'].' <strong>('.$fila['materia_requerida_id'].') Año:</strong> ' . $fila['materia_requerida_anio'];

				$rowCampo5 = (($fila['idCondicionMateriaRequerida']==1)?'Regular':'Aprobada').' <strong>('. $fila['idCondicionMateriaRequerida'].')</strong>';
				
?>						
       
            <tr>
                  <td align="center"><small><b><input type="checkbox" class=" check" id="check_<?=$rowIdCampo1?>" name="check_usu[]" value="<?=$rowIdCampo1?>"></b></small></td>
                      <td align="center" colspan="3">
                      <div class="btn-group pull-right" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm disabled" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          <a class=" dropdown-item small" href="#" onclick="entidadVer(<?=$rowIdCampo1?>)"><i class="fa fa-address-card-o"></i>&nbsp;Ver</a>
                          <a class=" dropdown-item small" href="#" onclick="entidadEditar('<?=$rowIdCampo1?>')"><i class="fa fa-edit"></i>&nbsp;Editar</a>
                          <a class=" dropdown-item small" href="#" onclick="entidadEliminar('<?=$rowIdCampo1?>')"><i class="fa fa-trash"></i>&nbsp;Borrar</a>
                        </div>
                                    </div>
                  </td>
                  <td align="left"><small><?=$rowCampo2;?><small></td>
                  <td align="left"><small><?=$rowCampo3;?></small></td>
                  <td align="left"><small><?=$rowCampo4;?></small></td>
				  <td align="center"><small><?=$rowCampo5;?></small></td>
                  
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
