<?php
header("Content-type: text/html; charset=utf8");
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

require_once "ProfesorFilter.php";
require_once 'SanitizeCustom.class.php';

require_once 'pagination.php';
require_once "_seguridad.php";
//die('acaaa');
//die(unserialize('a:1:{i:0;s:8:"empleado";}')[0]);

$rol_usuario = '';
//$rol_admin = ($_SESSION['user_rol']=='admin' || $_SESSION['user_rol']=='SYSTEM')?'':'disabledbutton';

$rol_admin = '';


/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';

$nombre = isset($_POST['nombre'])?SanitizeCustom::APELLIDO_NOMBRES($_POST['nombre']):false;
$dni = isset($_POST['dni'])?SanitizeCustom::DOCUMENTO_CUIL($_POST['dni']):false;
$telefono = isset($_POST['telefono'])?SanitizeCustom::NUMEROS($_POST['telefono']):false;
$email = isset($_POST['email'])?SanitizeCustom::STRING($_POST['email']):false;


//die('sadsadsadsadsad');

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$numrows = $total_pages = 0;
$arr_objetos = $arr_filtro = [];

if($action == 'listar'){

	if ($nombre) {
		$arr_filtro['nombre'] = $nombre;
	}

	if ($dni) {
		$arr_filtro['dni'] = $dni;
	}

	if ($telefono) {
		$arr_filtro['telefono'] = $telefono;
	}

	if ($email) {
		$arr_filtro['email'] = $email;
	}


	//PAGINATION VARIABLES
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	
	//APLICA FILTRO
	$objeto = new ProfesorFilter;
	$arr_objetos = $objeto->getProfesoresDetalle($page,$per_page,$arr_filtro);

	//var_dump($arr_objetos);die;
	//OBTIENE CANTIDAD DE RESULTADOS
	$numrows = $objeto->getCantidad();

	//SETEAR PAGINATION VARIABLES
	$total_pages = ceil($numrows/$per_page);
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;

	//*********************************************************** */
	//****************  PONER LOS NOMBRES DE LOS CAMPOS ********* */
	//*********************************************************** */
	$campo1 = "Id";$campo2 = "Nombres"; $campo3 = "Dni"; $campo4 = "Telefono"; $campo5 = "Email"; 
	//*********************************************************** */
	//*********************************************************** */

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
            <th class="text-center text-primary" width="30%"><small><b><?=$campo2?></b></small></th>
            <th class="text-center text-primary" width="5%"><small><b><?=$campo3?></b></small></th>
            <th class="text-center text-primary" width="25%"><small><b><?=$campo4?></b></small></th>
            <th class="text-center text-primary" width="30%"><small><b><?=$campo5?></b></small></th>
          </tr>
          <tr>
            <th class="text-right" colspan=4>
                <button class="btn btn-primary btn-sm" onclick="quitarFiltro()" title="Quitar Filtro"><img src="../public/assets/img/icons/filterminus.png" width="22"></button>
                <button class="btn btn-primary btn-sm" onclick="aplicarFiltro()" title="Aplicar Filtro"><img src="../public/assets/img/icons/filter.png" width="22"></button>
              </th>
            <th class="text-center" width="15%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo2?>" value="<?=$nombre?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo3?>" value="<?=$dni?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo4?>" value="<?=$telefono?>"></b></small></th>
            <th class="text-center" width="9%"><small><b><input type="text" class="form-control" id="inputFiltro<?=$campo5?>" value="<?=$email?>"></b></small></th>
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
                          <a class=" dropdown-item small" href="#" onclick="entidadVer('<?=$rowIdCampo1?>')"><i class="fa fa-address-card-o"></i>&nbsp;Ver</a>
                          <a class=" dropdown-item small" href="#" onclick="entidadEditar('<?=$rowIdCampo1?>')"><i class="fa fa-edit"></i>&nbsp;Editar</a>
                          <a class=" dropdown-item small" href="#" data-toggle="modal" data-target="#confirmarModal" data-id="<?=$rowIdCampo1?>"><i class="fa fa-trash"></i>&nbsp;Borrar</a>
                          <a class=" dropdown-item small disabledbutton" href="#" onclick="enviarEmail('<?=$rowIdCampo1?>')"><i class="fa fa-envelope"></i>&nbsp;Enviar Email</a>
                        </div>
                                    </div>
                  </td>
                  <td align="left"><small><?=$rowCampo2;?><small></td>
                  <td align="left"><small><?=$rowCampo3;?></small></td>
                  <td align="left"><small><?=$rowCampo4;?></small></td>
                  <td align="left"><small><a href="mailto:<?=$rowCampo5?>"><?=$rowCampo5;?></a></small></td>
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
