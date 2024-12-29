<?php 
require_once('Alumno.php');

class AlumnoFilter extends Alumno{
	/* Aplica Filtro */
	public function arreglo_filter($inicio,$final,$filtros) {
        $this->getConection();
		//sql para sacar la cantidad 
        $sqlcount = "SELECT count(*) as cantidad
	       			 FROM alumno a, persona per 
				     WHERE a.dni = per.dni ";
       
	    //sql con los los campos que me interesan 
        $sql = "SELECT a.id, a.dni, a.apellido, a.nombre, per.domicilio, per.idLocalidad, 
		               per.telefono_caracteristica, per.telefono_numero, per.email 
				FROM alumno a, persona per 
				WHERE a.dni = per.dni ";   

        if (isset($filtros['valor'])) {
            $sql .= " and (( a.nombre like '%".$filtros['valor']."%' or a.apellido like '%".$filtros['valor']."%' ) " . 
                    " or ( a.dni like '%".$filtros['valor']."%' ) " . 
                    " or ( per.telefono_numero like '%".$filtros['valor']."%' ) " .
                    " or ( per.email like '%".$filtros['valor']."%' )) "; 

			$sqlcount .= " and (( a.nombre like '%".$filtros['valor']."%' or a.apellido like '%".$filtros['valor']."%' ) " .
                    " or ( a.dni like '%".$filtros['valor']."%' ) " . 
                    " or ( per.telefono_numero like '%".$filtros['valor']."%' ) " .
                    " or ( per.email like '%".$filtros['valor']."%' )) "; 
        };               
        

        if (isset($inicio)&&isset($final)) {
            $sql .= "LIMIT ".$inicio. "," . $final; 
        };

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
	public function getAlumnosDetalle($page=1,$per_page=3,$filtro=[]){
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
/*$alumnoFilter = new AlumnoFilter();

//$alumno->save(['id'=>'1785','dni'=>'24912834','apellido'=>'Fontanellaz','nombres'=>'Javier H.','anio_ingreso'=>'2023','debe_titulo'=>'No','habilitado'=>'No']);
var_dump($alumnoFilter->getAlumnosDetalle(1,3,[]));*/


?>
