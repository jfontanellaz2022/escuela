<?php 
require_once('Db.php');
//require_once('../util/ArrayCustom.class.php');

class AlumnoCursaMateria {

	protected $table = 'alumno_cursa_materia';
	protected $conection;

	private $id;
	private $alumno_id;
	private $materia_id;
	private $cursado_tipo_id; 
	private $cursado_anio;
	private $cursado_tipo_descripcion;
	private $fecha_hora_inscripcion;
	private $nota;
	private $estado_final;
	private $fecha_modificacion_nota;
	private $fecha_vencimiento_regularidad = NULL;
	private $usuario_id;

	private $arr_regulares = [];

	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all Alumnos */
	public function getAlumnosCursanMaterias(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/* Get Alumno by Id */
	public function getAlumnoCursaMateriaById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get Alumno by Id Materia*/
	public function getAlumnoCursaMateriaByIdMateria($idMateria){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idMateria = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idMateria]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Get Alumno by Dni */
	public function getAlumnoCursaMateriasByIdAlumno($alumno_id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idAlumno = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Get Alumno by Dni */
	public function getAlumnoCursaMateriasByMaximoAnioCursadoByIdCarrera($alumno_id,$carrera_id){
		$this->getConection();
		$sql = "SELECT MAX(m.anio) as anio FROM alumno_cursa_materia acm, carrera_tiene_materia ctm, materia m 
		        WHERE acm.idAlumno = ? and acm.idMateria=ctm.idMateria and ctm.idCarrera = ? and ctm.idMateria=m.id and acm.estado_final<>'Cursando'";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$carrera_id]);
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		//var_dump($res['anio']);die;
		return $res['anio'];
	}

	/* Get Materias by Id Alumno y por Id Calendario */
	public function getMateriasByIdAlumnoByIdCalendario($alumno_id,$calendario_id,$llamado=3){
		$this->getConection();
		if ($llamado==3) {
			$sql = "SELECT * FROM " . $this->table . " WHERE idAlumno = ? and idCalendario = ?";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$alumno_id,$calendario_id]);
		} else {
			$sql = "SELECT * FROM " . $this->table . " WHERE idAlumno = ? and idCalendario = ? and llamado = ?";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$alumno_id,$calendario_id,$llamado]);
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/****************************************************************************************************************
    
    Saca todas las materias que cumplan con un estado especifico. Algunos estados requieren control de vencimiento 
    (La Regularidad y el estado Libre tienen vencimiento).

    estado: 'Aprobo', 'Libre', 'Regularizo', 'Promociono'
    vencimiento: TRUE, FALSE

    *****************************************************************************************************************/

    public function getMateriasCursadasByEstado($alumno_id,$estado,$vencimiento = TRUE)
    {
        $arr_resultado = array();
		$stmt = "";
		$this->getConection();
            if ($vencimiento) {
                $sql = "SELECT idMateria FROM " . $this->table . 
				        " WHERE idAlumno = ? AND estado_final = ? AND CURDATE() <= FechaVencimientoRegularidad ORDER BY idMateria ASC";
            } else {
				
                $sql = "SELECT idMateria FROM " . $this->table . " WHERE idAlumno = ? AND estado_final = ? ORDER BY idMateria ASC";
            }   
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$estado]);
		$arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		foreach ($arr_res as $fila) {
			$arr_resultado[] = $fila['idMateria'];
		}
       
        return $arr_resultado;
    }

	public function getMateriasCursadasByEstadoConDetallesTipoCursado($alumno_id,$estado,$vencimiento = TRUE)
    {
        $arr_resultado = array();
		$stmt = "";
		$this->getConection();
            if ($vencimiento) {
                $sql = "SELECT idMateria, tipo FROM " . $this->table . 
				        " WHERE idAlumno = ? AND estado_final = ? AND CURDATE() <= FechaVencimientoRegularidad ORDER BY idMateria ASC";
            } else {
				
                $sql = "SELECT idMateria, tipo FROM " . $this->table . " WHERE idAlumno = ? AND estado_final = ? ORDER BY idMateria ASC";
            }   
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$estado]);
		$arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		foreach ($arr_res as $fila) {
			$arr_detalle = [];
			$arr_detalle['idMateria'] = $fila['idMateria'];
			$arr_detalle['cursado'] = $fila['tipo'];
			$arr_resultado[] = $arr_detalle;
		}
       
        return $arr_resultado;
    }

	public function getMateriasCursadasByEstadoConDetalles($alumno_id,$estado,$vencimiento = TRUE)
    {
        $arr_resultado = array();
		$stmt = "";
		$this->getConection();
            if ($vencimiento) {
                $sql = "SELECT * FROM " . $this->table . 
				        " WHERE idAlumno = ? AND estado_final = ? AND CURDATE() <= FechaVencimientoRegularidad ORDER BY idMateria ASC";
            } else {
				
                $sql = "SELECT * FROM " . $this->table . " WHERE idAlumno = ? AND estado_final = ? ORDER BY idMateria ASC";
            }   
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$estado]);
		$arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		foreach ($arr_res as $fila) {
			$arr_detalle = [];
			$arr_detalle['idMateria'] = $fila['idMateria'];
			$arr_detalle['cursado'] = $fila['tipo'];
			$arr_detalle['nota'] = $fila['nota'];
			$arr_detalle['estado_final'] = $fila['estado_final'];
			$arr_detalle['fecha_vencimiento_regularidad'] = $fila['FechaVencimientoRegularidad'];
			$arr_resultado[] = $arr_detalle;
		}
        $this->arr_regulares = $arr_resultado;
        return $this->arr_regulares;
    }

	public function getMateriasCursadasByEstadoByAnio($alumno_id,$estado,$anio)
    {
        $arr_resultado = array();
		$stmt = "";
		$this->getConection();
        $sql = "SELECT idMateria, tipo FROM " . $this->table . " WHERE idAlumno = ? AND estado_final = ? AND anioCursado = ? ORDER BY idMateria ASC";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$estado,$anio]);
		$arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		foreach ($arr_res as $fila) {
			$arr_item = [];
			$arr_item['idMateria'] = $fila['idMateria'];
			$arr_item['cursado'] = $fila['tipo'];
			$arr_resultado[] = $arr_item;
		}

		return $arr_resultado;
    }

	public function getMateriasCursadasByEstadoDetalle($alumno_id,$estado = "",$vencimiento = TRUE)
    {
        $arr_resultado = [];
		$stmt = "";
		$this->getConection();
		$expresion_estado_final = "";
		if ($estado!="") {
			$expresion_estado_final = " acm.estado_final = '".$estado."' AND ";
		};
        if ($vencimiento) {
                $sql = "SELECT m.id, m.nombre, m.anio, acm.nota, tca.nombre as condicion, c.descripcion, acm.FechaVencimientoRegularidad, acm.anioCursado
                          FROM alumno_cursa_materia acm, materia m, tipo_cursado_alumno tca, carrera c, carrera_tiene_materia ctm
                          WHERE acm.idAlumno = ? AND 
                                $expresion_estado_final
                                acm.idMateria = m.id AND 
                                acm.idTipoCursadoAlumno = tca.id AND
                                m.id = ctm.idMateria AND
                                ctm.idCarrera = c.id AND 
                                CURDATE() <= FechaVencimientoRegularidad

                        ";        
            } else {
                $sql = "SELECT m.id, m.nombre, m.anio, acm.nota, tca.nombre as condicion, c.descripcion, acm.FechaVencimientoRegularidad, acm.anioCursado
                          FROM alumno_cursa_materia acm, materia m, tipo_cursado_alumno tca, carrera c, carrera_tiene_materia ctm
                          WHERE acm.idAlumno = ? AND 
                                $expresion_estado_final
                                acm.idMateria = m.id AND 
                                acm.idTipoCursadoAlumno = tca.id AND
                                m.id = ctm.idMateria AND
                                ctm.idCarrera = c.id
                        ";
            }   
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$alumno_id]);
			foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
				$arr_materia = [];
                $arr_materia['idMateria'] = $fila['id'];
                $arr_materia['nombre'] = $fila['nombre'];
                $arr_materia['materia_anio'] = $fila['anio'];
                $arr_materia['nota'] = $fila['nota'];
                $arr_materia['condicion'] = $fila['condicion'];
                $arr_materia['carrera'] = $fila['descripcion'];
                $arr_materia['fecha_vencimiento'] = $fila['FechaVencimientoRegularidad'];
                $arr_resultado[] = $arr_materia;
			}

        return $arr_resultado;
    }


	/* Save Alumno */
	public function save($param){
		$this->getConection();
		// Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			$instancia = $this->getAlumnoCursaMateriaById($param["id"]);
			if(isset($instancia["id"])){
				$exists = true;	
				// Actual values 
				$this->id = $param["id"];
				$this->alumno_id = $instancia["idAlumno"];
				$this->materia_id = $instancia["idMateria"];
				$this->cursado_anio = $instancia["anioCursado "];
				$this->cursado_tipo_id = $instancia["idTipoCursadoAlumno"];
				$this->tipo = $instancia["tipo"];
				$this->fecha_hora_inscripcion = $instancia["FechaHoraInscripcion"];
				$this->nota = $instancia["nota"];
				$this->estado_final = $instancia["estado_final"];
				$this->fecha_modificacion_nota = $instancia["FechaModificacionNota"];
				$this->fecha_vencimiento_regularidad = $instancia["FechaVencimientoRegularidad"];
				$this->usuario_id = $instancia["idUsuario"];
			}
		}

		//var_dump($param);die;
		// Received values 
		//die($param["fecha_vencimiento_regularidad"]);
		if(isset($param["alumno_id"])) $this->alumno_id = $param["alumno_id"];
		if(isset($param["materia_id"])) $this->materia_id = $param["materia_id"];
		if(isset($param["anio_cursado"])) $this->anio_cursado = $param["anio_cursado"];
		if(isset($param["tipo"])) $this->tipo = $param["tipo"];
		if(isset($param["cursado_id"])) $this->cursado_tipo_id = $param["cursado_id"];
		if(isset($param["fecha_hora_inscripcion"])) $this->fecha_hora_inscripcion = $param["fecha_hora_inscripcion"];
		if(isset($param["nota"])) $this->nota = $param["nota"];
		if(isset($param["estado_final"])) $this->estado_final = $param["estado_final"];
		if(isset($param["fecha_modificacion_nota"])) $this->fecha_modificacion_nota = $param["fecha_modificacion_nota"];
		if(isset($param["fecha_vencimiento_regularidad"])) $this->fecha_vencimiento_regularidad = $param["fecha_vencimiento_regularidad"];
		else $this->fecha_vencimiento_regularidad = NULL;
		
		if(isset($param["usuario_id"])) $this->usuario_id = $param["usuario_id"];
		
		// Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idAlumno = ?, idMateria = ?, 
			                                     anioCursado = ?, idTipoCursadoAlumno = ?, tipo = ?, FechaHoraInscripcion = ?,
												 nota = ?, estado_final = ?, FechaModificacionNota = ?,
												 FechaVencimientoRegularidad = ?, idUsuario = ?
										     WHERE id = ?";
			$stmt = $this->conection->prepare($sql);
			try {
				$arr_arg = [$this->alumno_id,$this->materia_id,$this->anio_cursado,$this->cursado_tipo_id,$this->tipo,$this->fecha_hora_inscripcion,$this->nota,
				            $this->estado_final,$this->fecha_modificacion_nota,$this->fecha_vencimiento_regularidad,$this->usuario_id,$this->id];
				//var_dump($arr_arg);die;			
			    $stmt->execute($arr_arg);
			} catch (Exception $e) {
				return -1*$e->getCode();
			}	
			$id = $this->id;
		} else {
			$sql = "INSERT INTO ".$this->table. " (idAlumno, idMateria, anioCursado, idTipoCursadoAlumno, tipo, FechaHoraInscripcion, nota, estado_final, FechaModificacionNota, FechaVencimientoRegularidad, idUsuario) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			try {
				$arr_arg = [$this->alumno_id,$this->materia_id,$this->anio_cursado,$this->cursado_tipo_id,$this->tipo,$this->fecha_hora_inscripcion,$this->nota,
				            $this->estado_final,$this->fecha_modificacion_nota,$this->fecha_vencimiento_regularidad,$this->usuario_id];
				$stmt->execute($arr_arg);
				$id = $this->conection->lastInsertId();
				$this->id = $id;
			} catch (Exception $e) {
				return  -1*$e->getCode();
			}	
		}

		return $id;	

	} 


	
	/* Delete Alumno by id */
	public function deleteAlumnoCursaMateriaById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	public function deleteAlumnoCursaMateriaByIdAlumnoByIdMateriaByAnio($alumno_id,$materia_id,$anio){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE idAlumno = ? AND idMateria = ? AND anioCursado = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$alumno_id,$materia_id,$anio]);
	}
	

}

// ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR
//$alumno = new AlumnoCursaMateria();

//var_dump($alumno->getAlumnoCursaMateriaByIdMateria(409));die;

//$arr = ["id"=> 25971,"alumno_id"=>61,"materia_id"=>20,"anio_cursado"=>2025,"tipo"=>"Semipresencial","cursado_id"=>2,"nota"=>7, 
//        "estado_final"=> "Promociono", "fecha_hora_inscripcion"=>"2024-02-29" ,"fecha_modificacion_nota"=> "2024-02-29", "usuario_id"=> "5"];

//array(11) { ["id"]=> int(25971) ["alumno_id"]=> int(61) ["materia_id"]=> int(20) ["anio_cursado"]=> int(2027) ["tipo"]=> string(14) "Semipresencial" ["cursado_id"]=> int(2) ["nota"]=> int(4) ["estado_final"]=> string(8) "Homologo" ["fecha_hora_inscripcion"]=> string(10) "2024-02-29" ["fecha_modificacion_nota"]=> string(10) "2024-02-29" ["usuario_id"]=> string(1) "5" }

//$arr = ["alumno_id"=>61,"materia_id"=>20,"anio_cursado"=>2024,"cursado_id"=>2, "tipo"=>"Semipresencial","nota"=>0,
//        "estado_final"=>"Cursando", "fecha_hora_inscripcion"=> "2024-02-29","fecha_modificacion_nota"=>"2024-02-29","usuario_id"=>"5"];

//var_dump($alumno->getAlumnoCursaMateriasByMaximoAnioCursado(646));
//var_dump($alumno->getMateriasCursadoByEstado(1024,"Regularizo"));
//$arr = ['alumno_id'=>471,'materia_id'=>409,'anio_cursado'=>2024,'tipo'=>'Libre','fecha_hora_inscripcion'=>'2024-01-05','nota'=>0,'estado_final'=>'Cursando','fecha_modificacion_nota'=>'2024-01-05'];
//echo $alumno->save($arr);
//$alumno->deleteAlumnoCursaMateriaByIdAlumnoByIdMateriaByAnio(471,409,2024);


?>
