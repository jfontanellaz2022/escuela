<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
require_once "_seguridad.php";


$action = (isset($_POST['action'])&& $_POST['action'] !=NULL)?$_POST['action']:'';
$where = array();$sWhere = "";
$action = 'ajax';
if($action == 'ajax'){
	$tables = " evento e ";
	$campos = " e.* ";
	$codigo = ( isset($_POST['codigo']) && ($_POST['codigo']) )?($_POST['codigo']):false;

	if ($codigo) $where[] = 'e.codigo = ' . $codigo;

    if (count($where)>0) $sWhere =' WHERE ' . implode(" and ",$where);
 	$sWhere .= " ORDER BY e.codigo asc ";

	//PAGINATION VARIABLES
	$page = ( isset($_REQUEST['page']) && !empty($_REQUEST['page']) )?$_REQUEST['page']:1;
	$per_page = ( isset($_REQUEST['per_page']) && ($_REQUEST['per_page']>0) )?$_REQUEST['per_page']:1; //how much records you want to show
	$adjacents  = 4; //gap between pages after number of adjacents
	$offset = ($page - 1) * $per_page;
	//Count the total number of row in your table*/
    $sql = "SELECT count(*) AS numrows FROM $tables $sWhere ";
	$count_query = mysqli_query($conex,$sql);
	if ($row = mysqli_fetch_array($count_query)){$numrows = $row['numrows'];}
	$total_pages = ceil($numrows/$per_page);
	//main query to fetch the data
	$sql_2 = "SELECT $campos FROM  $tables $sWhere LIMIT $offset,$per_page";
	$query = mysqli_query($conex,$sql_2);

	//loop through fetched data
    echo "<table class='table'>";
	if ($numrows>0){
		$c=0;
			echo '<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead class="thead-dark">
						<tr>
							<th class="text-center" width="10%">#</th>
							<th class="text-center" width="10%"><small><b>CODIGO</b><small></th>
							<th class="text-center" width="50%"><small><b>DESCRIPCION</b><small></th>
							<th width="20%"><small><b>ACCIONES</b><small></th>
						</tr>
					</thead>';
			echo '<tbody>';
		$finales=0;
		$c=0;

		$pagina = (($page-1)*$per_page);

	    //$tipo_organismo = substr($_SESSION['organismo_codigo'],0,1);
    	while ($row=mysqli_fetch_assoc($query)) {
        	$c++;
			$indice = $pagina + $c;
			$espacio = "&nbsp;";
			$accion_editar = '<a href="#"><img src="../public/assets/img/icons/edit_icon.png" width="23"></a>';
			$accion_eliminar = '<a href="#" class="disabledbutton"><img src="../public/assets/img/icons/delete_icon.png" width="22"></a>';
        	echo '<tr>';
			echo '   <td>'.$indice.'</td>'.
			     '   <td align="center"><small>'.$row['codigo'].'</small></td>'.
				 '   <td align="left"><small>'.$row['descripcion'].'</small></td>'.
				 '   <td><small>'.$accion_editar.$espacio.$accion_eliminar.'</small></td>';
			echo '</tr>';
        $finales++;
    };
		echo "<tr><td colspan='11'>";
		$inicios=$offset+1;
		$finales+=$inicios-1;
		echo "<br>";
		echo "Mostrando $inicios al $finales de $numrows registros";
		echo "<br><p>";
		echo paginate($page, $total_pages, $adjacents);
		echo "</td></tr>";
    echo '</tbody>';
} else {
	echo '<tbody>';
	echo '<tr><td><div class="alert alert-danger" role="alert">
				 <b>Atenci&oacute;n:</b> No existen Eventos A&uacute;n.
			 </div></td></tr>';
	echo '</tbody>';
};
echo "</table></div>";
echo "<div id='res'></div>";

};

?>
