<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once 'pagination.php';
require_once 'AlumnoFilter.php';
require_once 'AlumnoEstudiaCarrera.php';

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
$valor = isset($_POST['valor'])?SanitizeVars::STRING($_POST['valor']):false;

$numrows = $total_pages = 0;
$arr_objetos = $arr_filtro = [];

if ($valor) {
	$arr_filtro['valor'] = $valor;
}

//var_dump($arr_filtro);die;
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

if($action == 'listar'){
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	
	$objeto = new AlumnoFilter();
	$arr_objetos = $objeto->getAlumnosDetalle($page,$per_page,$arr_filtro);
	$numrows = $objeto->getCantidad();

	//PAGINATION VARIABLES
	$total_pages = ceil($numrows/$per_page);
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

?>	

<div class="table-responsive" >
      <table class="table table-striped table-bordered table-hover bg2" id="tabla_calendario">
        <thead>
          <tr>
            <th class="text-left" colspan="13">
               <table class="table borderless" width="100%">
                  <tr>
                  <th class="text-left" colspan="5">
                    <button class="btn btn-primary rol_admin" onclick="entidadCrear()"><img src="../public/img/icons/add_icon.png" width="21"> Agregar</button>&nbsp;
                    <button class="btn btn-primary rol_admin" onclick="entidadEliminarSeleccionados()"><img src="../public/img/icons/delete.png" width="21"> Borrar Seleccionados</button>&nbsp;
                  </th>
                </tr>  
               </table>
            </th>
              </tr>
          <tr>
            <th class="text-center" width="5%"><small><b><input type="checkbox" class="" id="seleccionar_todos"></b></small></th>
            <th width="5%" class="text-center text-primary" colspan=3><small><b>ACCIONES</b><small></th>
            <th class="text-center text-primary" width="100%"><small><b>INFORMACIÓN</b></small></th>
          </tr>
          <tr>
            <th class="text-right" colspan=4>
                <button class="btn btn-primary btn-sm" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../public/img/icons/filterminus.png" width="24"></button>
                <button class="btn btn-primary btn-sm" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../public/img/icons/filter.png" width="21"></button>
              </th>
            <th class="text-center" width="100%"><small><b><input type="text" class="form-control" id="inputFiltroValor" value="<?=$valor?>"></b></small></th>
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
				$objAlumnoEstudiaCarrera = new AlumnoEstudiaCarrera();
				$arreglo_carreras = $objAlumnoEstudiaCarrera->getAlumnoEstudiaCarreraByIdAlumno($fila['id']);
				$rowCampo2 = $fila['apellido'].', '.$fila['nombre'].' <strong>('.$fila['id'].')</strong>';
				$rowCampo3 = $fila['dni'];
				$wsp = ($fila['telefono_caracteristica']!=NULL && $fila['telefono_numero']!=null)?'<a href="https://api.whatsapp.com/send/?phone=549'.$fila['telefono_caracteristica'].$fila['telefono_numero'].'&text=Hola&type=phone_number&app_absent=0" target="_blank"><img src="../public/img/icons/wsp_icon.png" width="20"></a>&nbsp;':'';
				$rowCampo4 = ($fila['telefono_caracteristica']!=NULL && $fila['telefono_numero']!=null)?$wsp.' ('.$fila['telefono_caracteristica'].') '.$fila['telefono_numero']:'';
				$rowCampo5 = $fila['email'];
				
?>			
            <tr>
                  <td align="center"><small><b><input type="checkbox" class=" check" id="check_<?=$rowIdCampo1?>" name="check_usu[]" value="<?=$rowIdCampo1?>"></b></small></td>
                      <td align="center" colspan="3">
                      <div class="btn-group pull-right" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                          <a class=" dropdown-item small" href="#" onclick="entidadVer('<?=$rowIdCampo1?>','<?=$rowCampo2?>')"><i class="fa fa-address-card-o"></i>&nbsp;Ver</a>
                          <a class=" dropdown-item small" href="#" onclick="entidadEditar('<?=$rowIdCampo1?>')"><i class="fa fa-edit"></i>&nbsp;Editar</a>
                          <a class=" dropdown-item small disabled" href="#" data-toggle="modal" data-target="#confirmarModal" data-id="<?=$rowIdCampo1?>"><i class="fa fa-trash"></i>&nbsp;Borrar</a>
                          <a class=" dropdown-item small" href="#" onclick="vincularCarrera('<?=$rowIdCampo1?>')"><i class="fa fa-graduation-cap"></i>&nbsp;Vincular Carrera</a>
						  <a class=" dropdown-item small disabled" href="#" onclick="inscribirCursado('<?=$rowIdCampo1?>')"><i class="fa fa-graduation-cap"></i>&nbsp;Asignar Cursado</a> 
						  <a class=" dropdown-item small disabled" href="#" onclick="inscribirExamen('<?=$rowIdCampo1?>')"><i class="fa fa-graduation-cap"></i>&nbsp;Inscribir a Exámen</a>
						  
                        </div>
                                    </div>
                  </td>
                  <td align="left"><strong><?php echo str_replace('per', '*****', $rowCampo2);?></strong><br>
				  <small><strong>Documento:</strong> <?=$rowCampo3?></small><br>
				  <small><strong>WhatsApp:</strong> <?=$rowCampo4?></small><br>
				  <small><strong>Email:</strong> <a href="mailto:<?=$rowCampo5?>"><?=$rowCampo5;?></a></small><br>
				  <small><strong>Carrera:</strong>
				    <ul>
				       <?php 
					        foreach ($arreglo_carreras as $itemCarrera) { 
								echo "<li>" . 
								     "    <a href='menuAlumnoMateriasCursadasPorAlumno.php?token=".$_SESSION['token']."&carrera_id=".$itemCarrera['id']."&alumno_id=".$fila['id']."'><img src='../public/img/icons/libros_no_organizados.jpg' width='40' title='Materias Cursadas'></a>&nbsp;" . 
								     "    <a href='menuAlumnoMateriasRendidasPorAlumno.php?token=".$_SESSION['token']."&carrera_id=".$itemCarrera['id']."&alumno_id=".$fila['id']."'><img src='../public/img/icons/libros_organizados.jpg' width='40' title='Materias Rendidas'></a>&nbsp;" . 
									 "    <a href='#' onclick=\"cargarHistoriaPorCarrera(".$itemCarrera['id'].",".$fila['id'].",'".$itemCarrera['descripcion']."','".$rowCampo2."')\"><span class=\"badge badge-info\">".$itemCarrera['descripcion']."</span></a>" . 
									 "</li>";		 
							}	
					    ?>
					</ul>	
				  </td>
              </tr>
		<?php 
				$finales++;
			};
		?>	  
        </tbody>
         <tfoot>
             <tr>
				<td colspan='8'>
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
				<tr><td colspan="8">
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
