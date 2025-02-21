<?php 
require_once('CorrelativasParaCursar.php');

class CorrelativasParaCursarFilter extends CorrelativasParaCursar{
   
    /* Aplica Filtro */
    public function arreglo_filter($inicio,$final,$filtros) {
        $this->getConection();
        $sqlcount = "SELECT count(*) as cantidad
                FROM correlativasparacursar cpc, materia m, materia m1 
                WHERE cpc.idMateria = m.id and 
                      cpc.idMateriaRequerida = m1.id ";
       
        $sql = "SELECT cpc.id, m.id as 'materia_id', m.nombre as 'materia_nombre', m.anio as 'materia_anio',
                       m1.id as 'materia_requerida_id', m1.nombre as 'materia_requerida_nombre', m1.anio as 'materia_requerida_anio',
                       m.carrera as carrera,
                       cpc.idCondicionMateriaRequerida, IF(cpc.idCondicionMateriaRequerida=1, 'REGULAR', 'APROBADA') as 'Condicion'
        FROM correlativasparacursar cpc, materia m, materia m1 
        WHERE cpc.idMateria = m.id and 
              cpc.idMateriaRequerida = m1.id";   

        if (isset($filtros['carrera'])) {
            $sql .= " and ( m.carrera like '%".$filtros['carrera']."%' ) ";
            $sqlcount .= " and ( m.carrera like '%".$filtros['carrera']."%' ) ";
        };               
        if (isset($filtros['materia'])) {
             $sql .= " and ( m.nombre like '%".$filtros['materia']."%' ) ";
             $sqlcount .= " and ( m.nombre like '%".$filtros['materia']."%' ) ";
        };
        if (isset($filtros['materia_requerida'])) {
            $sql .= " and ( m1.nombre like '%".$filtros['materia_requerida']."%' ) ";
            $sqlcount .= " and ( m1.nombre like '%".$filtros['materia_requerida']."%' ) ";
        };
        if (isset($filtros['condicion'])&&$filtros['condicion']!=0) {
            $sql .= " and ( cpc.idCondicionMateriaRequerida = ".$filtros['condicion']." ) ";
            $sqlcount .= " and ( cpc.idCondicionMateriaRequerida = ".$filtros['condicion']." ) ";
        };
        
        $sql .= " ORDER BY m.anio ASC, m.nombre ASC, m1.anio ASC, m1.nombre ASC";

        if (isset($inicio)&&isset($final)) {
            $sql .= " LIMIT ".$inicio. "," . $final;
        }

        

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


     /* Get all */
	public function getCorrelativasCursarDetalle($page=1,$per_page=3,$filtro){
        $arr_resultados = [];
        $c = 0;
        $inicio = ($page*$per_page)-$per_page;
        $final = ($per_page);
        $arr_resultado = $this->arreglo_filter($inicio,$final,$filtro);
        return $arr_resultado;
	}

}

?>
