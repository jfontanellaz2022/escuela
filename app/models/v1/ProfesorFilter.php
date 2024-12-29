<?php 
require_once('Profesor.php');

class ProfesorFilter extends Profesor{
	/* Aplica Filtro */
	public function arreglo_filter($inicio,$final,$filtros) {
        $this->getConection();
		//sql para sacar la cantidad 
        $sqlcount = "SELECT count(*) as cantidad
	       			 FROM profesor p, persona per 
				     WHERE p.dni = per.dni ";
       
	    //sql con los los campos que me interesan 
        $sql = "SELECT p.id, p.dni, p.apellido, p.nombre, per.domicilio, per.idLocalidad, 
		               per.telefono_caracteristica, per.telefono_numero, per.email 
				FROM profesor p, persona per 
				WHERE p.dni = per.dni ";   

        if (isset($filtros['nombre'])) {
            $sql .= " and ( p.nombre like '%".$filtros['nombre']."%' or p.apellido like '%".$filtros['nombre']."%' ) ";
			$sqlcount .= " and ( p.nombre like '%".$filtros['nombre']."%' or p.apellido like '%".$filtros['nombre']."%' ) ";
        };               
        if (isset($filtros['dni'])) {
             $sql .= " and ( p.dni like '%".$filtros['dni']."%' ) ";
             $sqlcount .= " and ( p.dni like '%".$filtros['dni']."%' ) ";
        };
        if (isset($filtros['telefono'])) {
            $sql .= " and ( per.telefono_numero like '%".$filtros['telefono']."%' ) ";
            $sqlcount .= " and ( per.telefono_numero like '%".$filtros['telefono']."%' ) ";
        };
        if (isset($filtros['email'])) {
            $sql .= " and ( per.email like '%".$filtros['email']."%' ) ";
            $sqlcount .= " and ( per.email like '%".$filtros['email']."%' ) ";
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

     /* Get all Alumnos */
	 // filtro = [nombre,dni,telefono,email]
	public function getProfesoresDetalle($page=1,$per_page=3,$filtro=[]){
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

/* ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR */
//$profe = new ProfesorFilter();
//var_dump($profe->getProfesoresDetalle(1,1,['nombre'=>'Barce']));


?>
