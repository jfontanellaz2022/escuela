<?php 
require_once('Materia.php');

class MateriaFilter extends Materia {

    /* Aplica Filtro */
    public function arreglo_filter($inicio,$final,$filtros) {
        $this->getConection();
        $sqlcount = "SELECT count(*) as cantidad
                FROM materia m
                WHERE 1=1 ";
       
        $sql = "SELECT m.id, m.nombre, m.carrera as carrera, 
                       m.anio, m.idCursado as cursado_id, m.promocionable, 
                       m.idFormato as formato_id
                FROM materia m 
                WHERE 1=1 ";   

        if (isset($filtros['carrera'])) {
            $sql .= " and ( m.carrera like '%".$filtros['carrera']."%' ) ";
            $sqlcount .= " and ( m.carrera like '%".$filtros['carrera']."%' ) ";
        };               
        if (isset($filtros['materia'])) {
             $sql .= " and ( m.nombre like '%".$filtros['materia']."%' ) ";
             $sqlcount .= " and ( m.nombre like '%".$filtros['materia']."%' ) ";
        };
        if (isset($filtros['anio'])) {
            $sql .= " and ( m.anio = ".$filtros['anio']." ) ";
            $sqlcount .= " and ( m.anio = ".$filtros['anio']." ) ";
        };
        if (isset($filtros['cursado_id'])&&$filtros['cursado_id']!=0) {
            $sql .= " and ( m.idCursado = ".$filtros['cursado_id']." ) ";
            $sqlcount .= " and ( m.idCursado = ".$filtros['cursado_id']." ) ";
        };
        if (isset($filtros['promocionable'])) {
            $sql .= " and ( m.promocionable = '".$filtros['promocionable']."' ) ";
            $sqlcount .= " and ( m.promocionable = '".$filtros['promocionable']."' ) ";
        };
        if (isset($filtros['formato_id'])&&$filtros['formato_id']!=0) {
            $sql .= " and ( m.idFormato = ".$filtros['formato_id']." ) ";
            $sqlcount .= " and ( m.idFormato = ".$filtros['formato_id']." ) ";
        };

        if (isset($inicio)&&isset($final)) {
            $sql .= "LIMIT ".$inicio. "," . $final; 
        }
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


     /* Get all  */
	public function getMateriasDetalle($page=1,$per_page=3,$filtro){
        $arr_resultado = [];
        $inicio = ($page*$per_page)-$per_page;
        $final = ($per_page);
        $arr_resultado = $this->arreglo_filter($inicio,$final,$filtro);
        return $arr_resultado;
	}




}

?>
