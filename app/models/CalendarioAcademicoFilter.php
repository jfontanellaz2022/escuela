<?php 
require_once('CalendarioAcademico.php');

class CalendarioAcademicoFilter extends CalendarioAcademico {
/* Aplica Filtro */
public function arreglo_filter($inicio,$final,$filtros) {
	$this->getConection();
	$where = "";
	$andX = [];
	$andX[] = " c.idTipificacion = t.id ";


	if (isset($filtros['id'])) $andX[] = 'c.id = ' . $filtros['id'];
	if (isset($filtros['anio_lectivo'])) $andX[] = 'c.anio_lectivo = ' . $filtros['anio_lectivo'];
	if (isset($filtros['evento'])) $andX[] = " t.nombre like '%" . $filtros['evento'] . "%'";
	if (isset($filtros['fecha_inicio'])) $andX[] = "c.fecha_inicio like '" . $filtros['fecha_inicio'] . "'";
	if (isset($filtros['fecha_final'])) $andX[] = "c.fecha_final like '" . $filtros['fecha_final'] . "'";

	if (count($andX)>0) $where = ' WHERE (' . implode(" and ",$andX) . ') ';
	else $where = '';
	
	$sqlcount = "SELECT count(*) as cantidad
	FROM calendario_academico c, tipificacion t
	$where ";

	//sql con los los campos que me interesan 
	$sql = "SELECT c.id, c.anio_lectivo, c.fecha_inicio, c.fecha_final, c.idTipificacion,
		           t.id as 'tipificacion_id', t.codigo, t.nombre, t.descripcion
	FROM calendario_academico c, tipificacion t 
	$where 
	ORDER BY c.anio_lectivo DESC, c.fecha_inicio DESC
	";  

	

	if (isset($inicio)&&isset($final)) {
		$sql .= "LIMIT ".$inicio. "," . $final; 
	};
	
	//var_dump($sql);exit;
	$stmtcount = $this->conection->prepare($sqlcount);
	$stmtcount->execute();
	$res = $stmtcount->fetch(PDO::FETCH_ASSOC);  
	
	if (!empty($res)) {
		$this->cantidad = $res['cantidad'];
	} else {
		$this->cantidad = 0;
	}
	

	$stmt = $this->conection->prepare($sql);
	$stmt->execute();
	$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	return $res;

}

/* Get all Calendario */
	 public function getCalendarioAcademicoDetalle($page=1,$per_page=3,$filtro=[]){
        $arr_resultados = [];
        $c = 0;
        $inicio = ($page*$per_page)-$per_page;
        $final = ($per_page);
        //var_dump($filtro);die;
        $arr_resultado = $this->arreglo_filter($inicio,$final,$filtro);
        //var_dump($arr_resultado);die;
        return $arr_resultado;
	}

}

?>
