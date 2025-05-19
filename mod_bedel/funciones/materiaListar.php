<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once 'verificarCredenciales.php';
require_once 'MateriaFilter.php';
require_once 'SanitizeCustom.class.php';
require_once 'pagination.php';
require_once 'ArrayHash.class.php';


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

$action = (isset($_POST['action']) && $_POST['action'] !=NULL)?$_POST['action']:'';
$carrera = isset($_POST['carrera'])?SanitizeCustom::APELLIDO_NOMBRES($_POST['carrera']):false;
$materia = isset($_POST['materia'])?SanitizeCustom::APELLIDO_NOMBRES($_POST['materia']):false;
$anio = isset($_POST['anio'])?SanitizeCustom::INT($_POST['anio']):false;
$cursado_id = isset($_POST['cursado_id'])?SanitizeCustom::INT($_POST['cursado_id']):false;
$promocionable = isset($_POST['promocionable'])?SanitizeCustom::STRING($_POST['promocionable'],1,1):false;
$formato_id = isset($_POST['formato_id'])?SanitizeCustom::INT($_POST['formato_id']):false;

$sql = $sqlCantidadFilas = "";
$numrows = $total_pages = 0;
$arr_objetos = $arr_filtro = [];

if ($carrera) {
	$arr_filtro['carrera'] = $carrera;
}
if ($materia) {
	$arr_filtro['materia'] = $materia;
}
if ($anio) {
	$arr_filtro['anio'] = $anio;
}
if ($cursado_id) {
	$arr_filtro['cursado_id'] = $cursado_id;
}
if ($promocionable) {
	$arr_filtro['promocionable'] = $promocionable;
}
if ($formato_id) {
	$arr_filtro['formato_id'] = $formato_id;
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

	$objeto = new MateriaFilter();
	$arr_objetos = $objeto->getMateriasDetalle($page,$per_page,$arr_filtro);
	//var_dump($arr_objetos);die;

	$numrows = $objeto->getCantidad();

	//PAGINATION VARIABLES
	$total_pages = ceil($numrows/$per_page);
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;


	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$campo1 = "Id";$campo2 = "Carrera"; $campo3 = "Materia"; $campo4 = "Anio"; $campo5 = "Cursado"; $campo6 = "Promoc."; $campo7 = "Formato"; 
	//*********************************************************** */
	//*****
?>	

<div class="table-responsive" >
      <table class="table table-striped table-bordered table-hover bg2" id="tabla_calendario">
        <thead>
          <tr>
            <th class="text-left" colspan="14">
               <table class="table borderless" width="100%">
                  <tr>
                  <th class="text-left" colspan="5">
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
            <th class="text-center text-primary" width="18%"><small><b><?=$campo2?></b></small></th>
            <th class="text-center text-primary" width="25%"><small><b><?=$campo3?></b></small></th>
            <th class="text-center text-primary" width="8%"><small><b><?=$campo4?></b></small></th>
            <th class="text-center text-primary" width="12%"><small><b><?=$campo5?></b></small></th>
			<th class="text-center text-primary" width="12%"><small><b><?=$campo6?></b></small></th>
			<th class="text-center text-primary" width="12%"><small><b><?=$campo7?></b></small></th>
          </tr>
          <tr>
            <th class="text-right" colspan=4>
                <button class="btn btn-primary btn-sm" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../public/img/icons/filterminus.png" width="22"></button>
                <button class="btn btn-primary btn-sm" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../public/img/icons/filter.png" width="22"></button>
              </th>
            <th class="text-center" width="15%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo2?>" value="<?=$carrera?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo3?>" value="<?=$materia?>"></b></small></th>
            <th class="text-center" width="9%"><select class="form-control" id="inputFiltro<?=$campo4?>">
																					<option>--Opción--</option>
																					<option value='1' <?=($anio==1)?"selected":"";?> >Primero</option>
																					<option value='2' <?=($anio==2)?"selected":"";?> >Segundo</option> 
																					<option value='3' <?=($anio==3)?"selected":"";?> >Tercero</option>
																					<option value='4' <?=($anio==4)?"selected":"";?> >Cuarto</option>
												</select></th>
            <th class="text-center" width="9%"><select class="form-control" id="inputFiltro<?=$campo5?>">
																					<option>--Opción--</option>
																					<option value='1' <?=($cursado_id==1)?"selected":"";?> >1er Cuatr.</option>
																					<option value='2' <?=($cursado_id==2)?"selected":"";?> >2do. Cuat.</option> 
																					<option value='3' <?=($cursado_id==3)?"selected":"";?> >Anual</option>
												</select></th>
			<th class="text-center" width="9%"><select class="form-control" id="inputFiltro<?=$campo6?>">
																					<option>--Opción--</option>
																					<option value='S' <?=($promocionable=='s' || $promocionable=='S')?"selected":"";?> >Sí</option>
																					<option value='N' <?=($promocionable=='n' || $promocionable=='N')?"selected":"";?> >No</option> 
												</select></th>
			<th class="text-center" width="9%"><select class="form-control" id="inputFiltro<?=$campo7?>">
																					<option>--Opción--</option>
																					<option value='1' <?=($formato_id==1)?"selected":"";?> >Materia</option>
																					<option value='2' <?=($formato_id==2)?"selected":"";?> >Taller</option> 
																					<option value='3' <?=($formato_id==3)?"selected":"";?> >Seminario</option>
																					<option value='4' <?=($formato_id==4)?"selected":"";?> >Trab.Campo</option>
																					<option value='5' <?=($formato_id==5)?"selected":"";?> >T.Práctica</option>
																					<option value='6' <?=($formato_id==6)?"selected":"";?> >Proyectos</option>
																					<option value='7' <?=($formato_id==7)?"selected":"";?> >Módulos</option>
																					<option value='8' <?=($formato_id==8)?"selected":"";?> >Laboratorio</option>
												</select></th>
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
				$rowCampo3 = $fila['nombre'].' <strong>('.$fila['id'].')</strong>';
				
				$rowCampo4 = $fila['anio'];
				$rowCampo5 = $fila['cursado_id'];
				$rowCampo6 = $fila['promocionable'];
				$rowCampo7 = $fila['formato_id'];
				
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
                          <a class=" dropdown-item small" href="#" onclick="vincularCarrera('<?=$rowIdCampo1?>')"><i class="fa fa-graduation-cap"></i>&nbsp;Vincular Carrera</a>
						  <a class=" dropdown-item small" href="#" onclick="inscribirCursado('<?=$rowIdCampo1?>')"><i class="fa fa-graduation-cap"></i>&nbsp;Asignar Cursado</a> 
						  <a class=" dropdown-item small" href="#" onclick="inscribirExamen('<?=$rowIdCampo1?>')"><i class="fa fa-graduation-cap"></i>&nbsp;Inscribir a Exámen</a>
						  
                        </div>
                                    </div>
                  </td>
                  <td align="left"><small><?=$rowCampo2;?><small></td>
                  <td align="left"><small><?=$rowCampo3;?></small></td>
                  <td align="left"><small><?=$rowCampo4;?></small></td>
				  <td align="left"><small><?=$rowCampo5;?></small></td>
				  <td align="left"><small><?=$rowCampo6;?></small></td>
				  <td align="left"><small><?=$rowCampo7;?></small></td>
	
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
				<tr><td colspan=10">
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
