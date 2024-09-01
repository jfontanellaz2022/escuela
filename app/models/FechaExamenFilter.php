<?php 
require_once('FechaExamen.php');

class FechaExamenFilter extends FechaExamen{
	/* Aplica Filtro */
	public function arreglo_filter($inicio,$final,$filtros) {
        $this->getConection();
		//sql para sacar la cantidad 
        $sqlcount = "SELECT count(*) as cantidad
	       			 FROM materia_tiene_fechaexamen mf,materia m, calendarioacademico c, evento e
				     WHERE (mf.idCalendarioAcademico=c.id) and (c.idEvento = e.id) and (mf.idMateria=m.id) ";
       
	    //sql con los los campos que me interesan 
        $sql = "SELECT mf.id as id_fecha_examen, mf.fechaExamen as fecha_examen, c.id as id_calendario, c.AnioLectivo as anio_lectivo, 
                       e.descripcion as descripcion_evento, mf.idCalendarioAcademico, mf.llamado, m.id as id_materia, 
                       m.nombre as nombre_materia, m.carrera 
				FROM materia_tiene_fechaexamen mf,materia m, calendarioacademico c, evento e
				WHERE (mf.idCalendarioAcademico=c.id) and (c.idEvento = e.id) and (mf.idMateria=m.id) ";   

        if (isset($filtros['nombre_materia'])) {
            $sql .= " and ( m.nombre like '%".$filtros['valor']."%' ) ";

			$sqlcount .= " and ( m.nombre like '%".$filtros['valor']."%' ) ";
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
	public function getFechaExamenesDetalle($page=1,$per_page=3,$filtro=[]){
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
