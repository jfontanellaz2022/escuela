<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'./');

require_once 'EventoFilter.php';
require_once 'Sanitize.class.php';
require_once 'pagination.php';
require_once 'ArrayHash.class.php';
require_once "_seguridad.php";

//die(unserialize('a:1:{i:0;s:8:"empleado";}')[0]);

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
  

//echo "*".capitalizeCadenas('javier Hernan fontanellaz')."*";
//die;
//$rol_usuario = '';
//$rol_admin = ($_SESSION['user_rol']=='admin' || $_SESSION['user_rol']=='SYSTEM')?'':'disabledbutton';

//$rol_admin = '';


/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$codigo = isset($_POST['codigo'])?SanitizeVars::INT($_POST['codigo']):false;
$descripcion = isset($_POST['descripcion'])?SanitizeVars::STRING($_POST['descripcion']):false;

$sql = $sqlCantidadFilas = "";
$numrows = $total_pages = 0;
$arr_objetos = $arr_filtro = [];

if ($codigo) {
	$arr_filtro['codigo'] = $codigo;
}
if ($descripcion) {
	$arr_filtro['descripcion'] = $descripcion;
}


//var_dump($arr_filtro);die;
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

if($action == 'listar'){
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	
	//$apc = new CorrelativasParaCursar();
	//$arr_correlativas_cursado = $apc->getCorrelativasCursadoDetalle($page,$per_page,$arr_filtro);

	$objeto = new EventoFilter();
	$arr_objetos = $objeto->getEventosDetalle($page,$per_page,$arr_filtro);
	//var_dump($arr_objetos);die;

	$numrows = $objeto->getCantidad();

	//PAGINATION VARIABLES
	$total_pages = ceil($numrows/$per_page);
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;


	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$campo1 = "Id";$campo2 = "Codigo"; $campo3 = "Descripcion"; 
	//*********************************************************** */
	//*****
?>	

<div class="table-responsive" >
      <table class="table table-striped table-bordered table-hover bg2" id="tabla_calendario">
        <thead>
          <tr>
            <th class="text-left" colspan="11">
               <table class="table borderless" width="100%">
                  <tr>
                  <th class="text-left" colspan="5">
                    <button class="btn btn-primary rol_admin" onclick="entidadCrear()" disabled>Agregar</button>&nbsp;
                    <button class="btn btn-primary rol_admin" onclick="entidadEliminarSeleccionados()" disabled>Borrar Seleccionados</button>&nbsp;
                  </th>
                </tr>  
               </table>
            </th>
              </tr>
          <tr>
            <th class="text-center" width="5%"><small><b><input type="checkbox" class="" id="seleccionar_todos"></b></small></th>
            <th width="5%" class="text-center text-primary" colspan=3><small><b>ACCIONES</b><small></th>
            <th class="text-center text-primary" width="5%"><small><b><?=$campo2?></b></small></th>
            <th class="text-center text-primary" width="90%"><small><b><?=$campo3?></b></small></th>
          </tr>
          <tr>
            <th class="text-right" colspan=4>
                <button class="btn btn-primary btn-sm" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../public/img/icons/filterminus.png" width="22"></button>
                <button class="btn btn-primary btn-sm" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../public/img/icons/filter.png" width="22"></button>
              </th>
            <th class="text-center" width="15%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo2?>" value="<?=($codigo==0)?'':$codigo;?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo3?>" value="<?=$descripcion?>"></b></small></th>
            
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
				$rowCampo2 = $fila['codigo'];
				$rowCampo3 = $fila['descripcion'] . '<strong>(' . $rowIdCampo1 .') </strong>' ;
			
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
                          <a class=" dropdown-item small disabled" href="#" data-toggle="modal" data-target="#confirmarModal" data-id="<?=$rowIdCampo1?>"><i class="fa fa-trash"></i>&nbsp;Borrar</a>
                        </div>
                                    </div>
                  </td>
                  <td align="left"><small><?=$rowCampo2;?><small></td>
                  <td align="left"><small><?=$rowCampo3;?></small></td>
              </tr>
		<?php 
				$finales++;
			};
		?>	  
        </tbody>
         <tfoot>
             <tr>
				<td colspan='6'>
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
				<tr><td colspan="4">
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
