<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'pagination.php';
require_once 'CalendarioAcademicoFilter.php';

//die(unserialize('a:1:{i:0;s:8:"empleado";}')[0]);

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$busqueda = isset($_POST['busqueda_rapida'])?$_POST['busqueda_rapida']:false;

$id = isset($_POST['id'])?$_POST['id']:"";
$anio_lectivo = isset($_POST['anio']) ? $_POST['anio']:"";
$evento = isset($_POST['evento'])?SanitizeVars::STRING($_POST['evento']):false;
$fecha_inicio = isset($_POST['fechaInicio'])?SanitizeVars::DATE($_POST['fechaInicio']):false;
$fecha_final = isset($_POST['fechaFinalizacion'])?SanitizeVars::DATE($_POST['fechaFinalizacion']):false;

$arr_objetos = $arr_filtro = [];

//$id = 147;
//$anio_lectivo = '2024';
//$fecha_inicio = '2024-06-27';
//$fecha_final = '2024-07-07';
//$evento = 'Intermedi';

if ($id) {
	$arr_filtro['id'] = $id;
}
if ($anio_lectivo) {
	$arr_filtro['anio_lectivo'] = $anio_lectivo;
}
if ($evento) {
	$arr_filtro['evento'] = $evento;
}
if ($fecha_inicio) {
	$arr_filtro['fecha_inicio'] = $fecha_inicio;
}
if ($fecha_final) {
	$arr_filtro['fecha_final'] = $fecha_final;
}

//die($id.'-'.$anioLectivo.'-'.$evento.'-'.$fechaInicio.'-'.$fechaFinalizacion);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/


$sql = $sqlCantidadFilas = "";

if($action == 'listar'){
	

	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	
	$objeto = new CalendarioAcademicoFilter();
	
	$arr_objetos = $objeto->getCalendarioAcademicoDetalle($page,$per_page,$arr_filtro);
	//die('sdfsdfsdf');
	$numrows = $objeto->getCantidad();
	
	//PAGINATION VARIABLES
	$total_pages = ceil($numrows/$per_page);
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;


	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$labelCampo1 = "ID";$labelCampo2 = "AÑO"; $labelCampo3 = "EVENTO"; $labelCampo4 = "F.INICIO"; $labelCampo5 = "F.FINALIZACION";
	$campo1 = "Id";$campo2 = "Anio"; $campo3 = "Evento"; $campo4 = "FechaInicio"; $campo5 = "FechaFinalizacion";
	//*********************************************************** */

	//var_dump($arr_objetos);exit;


?>	

<div class="table-responsive" >
      <table class="table table-striped table-bordered table-hover bg2" id="tabla_calendario">
        <thead>
          <tr>
            <th class="text-left" colspan="13">
               <table class="table borderless" width="100%">
                  <tr>
                  <th class="text-left" colspan="12">
                    <button class="btn btn-primary " onclick="entidadCrear()">Agregar</button>&nbsp;
                    <button class="btn btn-primary " onclick="entidadEliminarSeleccionados()">Borrar Seleccionados</button>&nbsp;
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
			<th class="text-center" width="8%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo2?>" value="<?=$anio_lectivo?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo3?>" value="<?=$evento?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo4?>" value="<?=$fecha_inicio?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo5?>" value="<?=$fecha_final?>"></b></small></th>
          </tr>
        </thead>
		<tbody>




<?php
		if (!empty($arr_objetos)){
			$finales = $c = 0;
			foreach ($arr_objetos as $fila) {
						$c++;
						$rowIdCampo1 = $fila['id']; 
						//$hashId = ArrayHash::encode(array($MY_SECRET=>$rowIdCampo1));
						$rowCampo2 = $fila['anio_lectivo'];
						$rowCampo3 = $fila['nombre'].' ('.$fila['codigo'].')';
						$rowCampo4 = $fila['fecha_inicio'];
						$rowCampo5 = $fila['fecha_final'];

						
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
			}; // END FOREACH
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
