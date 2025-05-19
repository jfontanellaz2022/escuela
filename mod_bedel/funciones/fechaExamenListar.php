<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'Sanitize.class.php';
require_once 'pagination.php';
require_once 'ArrayHash.class.php';
require_once 'MateriaFechaExamenFilter.php';


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



/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$busqueda = isset($_POST['busqueda_rapida'])?$_POST['busqueda_rapida']:null;

$sql = $sqlCantidadFilas = "";
$numrows = $total_pages = 0;
$arr_objetos = $arr_filtro = [];

if ($busqueda) {
	$arr_filtro['busqueda'] = $busqueda;
}

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

if($action == 'listar'){
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	//var_dump($page,$per_page,$arr_filtro);exit;
	$objeto = new MateriaFechaExamenFilter();
	$arr_objetos = $objeto->getFechaExamenesDetalle($page,$per_page,$arr_filtro);
	
	$numrows = $objeto->getCantidad();
    //var_dump($arr_objetos,$numrows);exit;
	//PAGINATION VARIABLES
	$total_pages = ceil($numrows/$per_page);
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

	//var_dump('aca ',$numrows,$arr_objetos);die;
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
            <th class="text-center" width="5%"></th>
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
if (!empty($arr_objetos)){
	$finales = $c = 0;
	foreach ($arr_objetos as $fila) {
				
				$c++;
				//$indice = $pagina + $c;
				$rowIdCampo1 = $fila['id_fecha_examen']; 
				//$hashId = ArrayHash::encode(array($MY_SECRET=>$rowIdCampo1));
				$rowCampo2 = $fila['nombre_materia'] . ' <strong>('.$fila['id_materia'] . ')</strong>';
				$rowCampo3 = $fila['carrera'];
				$rowCampo4 = $fila['descripcion_evento'] . ' del año ' .  $fila['anio_lectivo'] . ' <strong>(' . $fila['id_calendario'] . ')</strong>';
				$rowCampo5 = $fila['llamado'];
				$rowCampo6 = $fila['fecha_examen'];



				
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