<?php 
require_once('Db.php');
//require_once('../util/ArrayCustom.class.php');

class AlumnoCursaMateria {

	protected $table = 'alumno_cursa_materia';
	protected $conection;

	private $id;
	private $alumno_id;
	private $materia_id;
	private $cursado_id; 
	private $cursado_anio;
	private $cursado_nombre;
	private $fecha_inscripcion;
	private $nota;
	private $estado_nombre;
	private $fecha_modificacion_nota;
	private $fecha_vencimiento_regularidad = NULL;
	private $estado_id;
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

	/* Get All Alumnos by Materia - ACTUALIZADO */
	public function getAllAlumnosByMateria($param){
		$this->getConection();
		
		$arr_resultado = [];
		
		if ( isset($param['materia_id']) && isset($param['anio_cursado']) ) {
			$sql = "SELECT a.id, a.anio_ingreso, a.debe_titulo,a.habilitado, p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fechaNacimiento, p.nacionalidad, p.idLocalidad, p.domicilio, p.email, p.telefono_caracteristica, 
					   p.telefono_numero, p.observaciones, p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por, 
					   acm.anio_cursado, acm.tipo as cursado, acm.estado_final, acm.fecha_inscripcion, acm.nota,
					   acm.fecha_modificacion_nota, acm.idCursado as cursado_id,  acm.idEstado as estado_id,
					   p.email, p.telefono, 
					   tca1.id as 'id_cursado', tca1.codigo as 'codigo_cursado', tca1.nombre as 'nombre_cursado',
					   tca2.id as 'id_estado', tca2.codigo as 'codigo_estado', tca2.nombre as 'nombre_estado'	
				FROM alumno a, alumno_cursa_materia acm, persona p, tipificacion tca1, tipificacion tca2
				WHERE acm.idMateria = ? and acm.anio_cursado = ? and acm.idAlumno = a.id and 
				      a.idPersona = p.id and a.habilitado = 'Si' and 
				      acm.idCursado = tca1.id and
					  acm.idEstado = tca2.id
				ORDER BY p.apellido asc, p.nombre asc";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$param['materia_id'],$param['anio_cursado']]);
			$arr_resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);	

		}

		return $arr_resultado;
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
		//$stmt->debugDumpParams();
		$res_cursadas = $stmt->fetch(PDO::FETCH_ASSOC);

		$sql = "SELECT MAX(m.anio) as anio 
				FROM alumno_rinde_materia arm, carrera_tiene_materia ctm, materia m 
				WHERE arm.idAlumno = ? and arm.idMateria=ctm.idMateria and ctm.idCarrera = ? and ctm.idMateria=m.id and arm.estado_final='Aprobo';";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$carrera_id]);
		$res_aprobadas = $stmt->fetch(PDO::FETCH_ASSOC);

		

		$mayor = 0;

		if ($res_aprobadas['anio']!=NULL) {
			 
		     $mayor = ($res_cursadas['anio']<=$res_aprobadas['anio'])?$res_aprobadas['anio']:$res_cursadas['anio'];
		} else {
			 $mayor = $res_cursadas['anio'];
		}

		return $mayor;
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
				        " WHERE idAlumno = ? AND estado_final = ? AND CURDATE() <= fecha_vencimiento_regularidad ORDER BY idMateria ASC";
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
                $sql = "SELECT acm.idMateria, acm.tipo, acm.idEstado, acm.anio_cursado, t1.codigo as estado_codigo, acm.idCursado, t2.codigo as cursado_codigo FROM " . $this->table . 
				        " acm, tipificacion t1, tipificacion t2
						WHERE acm.idAlumno = ? AND 
						      acm.estado_final = ? AND 
							  CURDATE() <= acm.fecha_vencimiento_regularidad AND 
							  acm.idEstado = t1.id AND
							  acm.idCursado = t2.id 
						ORDER BY acm.idMateria ASC";
            } else {
                $sql = "SELECT acm.idMateria, acm.tipo, acm.idEstado, acm.anio_cursado, t1.codigo as estado_codigo, acm.idCursado, t2.codigo as cursado_codigo FROM " . $this->table . 
				       " acm, tipificacion t1, tipificacion t2
					   WHERE acm.idAlumno = ? AND 
					         acm.estado_final = ? AND
							 acm.idEstado = t1.id AND
							 acm.idCursado = t2.id 
					   ORDER BY acm.idMateria ASC";
            }   
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$estado]);
		$arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		foreach ($arr_res as $fila) {
			$arr_detalle = [];
			$arr_detalle['idMateria'] = $fila['idMateria'];
			$arr_detalle['cursado'] = $fila['tipo'];
			$arr_detalle['estado_id'] = $fila['idEstado'];
			$arr_detalle['estado_codigo'] = ''.$fila['estado_codigo'];
			$arr_detalle['cursado_id'] = $fila['idCursado'];
			$arr_detalle['anio_cursado'] = $fila['anio_cursado'];
			$arr_detalle['cursado_codigo'] = $fila['cursado_codigo'];
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
				        " WHERE idAlumno = ? AND estado_final = ? AND CURDATE() <= fecha_vencimiento_regularidad ORDER BY idMateria ASC";
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
			$arr_detalle['cursado_id'] = $fila['idCursado'];
			$arr_detalle['anio_cursado'] = $fila['anio_cursado'];
			$arr_detalle['estado_id'] = $fila['idEstado'];
			$arr_detalle['nota'] = $fila['nota'];
			$arr_detalle['estado_final'] = $fila['estado_final'];
			$arr_detalle['fecha_vencimiento_regularidad'] = $fila['fecha_vencimiento_regularidad'];
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
        $sql = "SELECT idMateria, tipo, idCursado, idEstado FROM " . $this->table . " WHERE idAlumno = ? AND estado_final = ? AND anio_cursado = ? ORDER BY idMateria ASC";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$estado,$anio]);
		$arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		foreach ($arr_res as $fila) {
			$arr_item = [];
			$arr_item['idMateria'] = $fila['idMateria'];
			$arr_item['cursado'] = $fila['tipo'];
			$arr_item['cursado_id'] = $fila['idCursado'];
			$arr_item['estado_id'] = $fila['idEstado'];
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
                $sql = "SELECT m.id, m.nombre, m.anio, acm.nota, tca.nombre as condicion, c.descripcion, acm.fecha_vencimiento_regularidad, acm.anio_cursado
                          FROM alumno_cursa_materia acm, materia m, tipo_cursado_alumno tca, carrera c, carrera_tiene_materia ctm
                          WHERE acm.idAlumno = ? AND 
                                $expresion_estado_final
                                acm.idMateria = m.id AND 
                                acm.idCursado = tca.id AND
                                m.id = ctm.idMateria AND
                                ctm.idCarrera = c.id AND 
                                CURDATE() <= fecha_vencimiento_regularidad

                        ";        
            } else {
                $sql = "SELECT m.id, m.nombre, m.anio, acm.nota, tca.nombre as condicion, c.descripcion, acm.fecha_vencimiento_regularidad, acm.anio_cursado
                          FROM alumno_cursa_materia acm, materia m, tipo_cursado_alumno tca, carrera c, carrera_tiene_materia ctm
                          WHERE acm.idAlumno = ? AND 
                                $expresion_estado_final
                                acm.idMateria = m.id AND 
                                acm.idCursado = tca.id AND
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
                $arr_materia['fecha_vencimiento'] = $fila['fecha_vencimiento_regularidad'];
                $arr_resultado[] = $arr_materia;
			}

        return $arr_resultado;
    }

	// ***** IMPORTANTE (mod_bedel: fn 'sacar las materias cursadas por alumno en una carrera')
	// METODO PARA SACAR LAS MATERIAS QUE CURSO UN ALUMNO EN UNA CARRERA 
	public function getHistorialMateriasCursadasByAlumnoByCarrera($alumno_id,$carrera_id) {
		$arr_resultado = [];
		$stmt = "";
		$this->getConection();

		$sql = "SELECT acm.id, m.id as 'materia_id', m.nombre as 'materia_nombre', m.anio as 'materia_anio', 
						acm.anio_cursado, acm.nota, acm.estado_final, acm.fecha_vencimiento_regularidad, 
		               acm.idCursado as 'cursado_id', t1.nombre as 'cursado_nombre',
					   acm.idEstado as 'estado_id', t2.nombre as 'estado_nombre'
				FROM alumno_cursa_materia acm, materia m, tipificacion t1, tipificacion t2
				WHERE acm.idAlumno = ? AND
						acm.idMateria IN (SELECT idMateria FROM carrera_tiene_materia WHERE idCarrera = ?) AND
						acm.idMateria = m.id AND
						acm.idCursado = t1.id AND
						acm.idEstado = t2.id
				ORDER BY m.anio ASC, m.nombre ASC";

	    $stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$carrera_id]);
		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
			$arr_materia = [];
			$arr_materia['id'] = $fila['id'];
			$arr_materia['materia_id'] = $fila['materia_id'];
			$arr_materia['materia_nombre'] = $fila['materia_nombre'];
			$arr_materia['materia_anio'] = $fila['materia_anio'];
			$arr_materia['anio_cursado'] = $fila['anio_cursado'];
			$arr_materia['nota'] = $fila['nota'];
			$arr_materia['estado_final'] = $fila['estado_final'];
			$arr_materia['fecha_vencimiento_regularidad'] = $fila['fecha_vencimiento_regularidad'];
			$arr_materia['cursado_id'] = $fila['cursado_id'];
			$arr_materia['cursado_nombre'] = $fila['cursado_nombre'];
			$arr_materia['estado_id'] = $fila['estado_id'];
			$arr_materia['estado_nombre'] = $fila['estado_nombre'];
			$arr_resultado[] = $arr_materia;
		}

	return $arr_resultado;
}

public function getMateriasConInscriptosCursadoPorCarrera($idCarrera="",$anio="") {

	$this->getConection();
	$res = [];
	if (isset($idCarrera) && $idCarrera!="" && isset($anio) && $anio!="") {
			$sql = "SELECT DISTINCT c.id, c.nombre, COUNT( * ) as cantidad, c.anio 
			        FROM alumno_cursa_materia a, carrera_tiene_materia b, materia c 
					WHERE a.idMateria = b.idMateria AND 
					      b.idCarrera = ? AND 
						  b.idMateria = c.id AND 
						  a.anio_cursado = ? 
					GROUP BY c.nombre 
					ORDER BY c.anio";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$idCarrera,$anio]);
			$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}; 
        
    return $res;
}
	
/* Get Alumnos by Id Materia y por Id Calendario */
public function getAlumnosCursanByIdMateriaDetalle($materia_id="",$anio="") {
	$this->getConection();
	$res = [];
	if (isset($materia_id) && isset($anio) && $materia_id!="" && $anio!="") {
		$sql = "SELECT a.id, a.anio_ingreso, a.debe_titulo, a.habilitado, a.idPersona,
						p.dni, p.apellido, p.nombre, p.email, p.telefono_caracteristica, p.telefono_numero,
						t.nombre as 'condicion', acm.nota, acm.estado_final, acm.fecha_hora_inscripcion 
				FROM alumno_cursa_materia acm, alumno a, persona p, tipificacion t
				WHERE acm.idMateria = ? AND
					acm.idCursado = t.id AND
					acm.idAlumno = a.id AND
					acm.anio_cursado = ? AND
					a.idPersona = p.id
				ORDER BY p.apellido ASC, p.nombre ASC;";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$materia_id,$anio]);
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
	};
	return $res;

}

/* Save Alumno */
public function save($param){
		//var_dump('aca',$param);exit;
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
				$this->cursado_id = $instancia["idCursado"];
				$this->cursado_anio = $instancia["anio_cursado "];
				$this->fecha_inscripcion = $instancia["fecha_inscripcion"];
				$this->nota = $instancia["nota"];
				$this->estado_nombre = $instancia["estado_final"];
				$this->fecha_modificacion_nota = $instancia["fecha_modificacion_nota"];
				$this->fecha_vencimiento_regularidad = $instancia["fecha_vencimiento_regularidad"];
				$this->estado_id = $instancia["idEstado"];
				$this->usuario_id = $instancia["idUsuario"];
			}
		}

		//var_dump($param);die;
		// Received values 
		//die($param["fecha_vencimiento_regularidad"]);
		if(isset($param["alumno_id"])) $this->alumno_id = $param["alumno_id"];
		if(isset($param["materia_id"])) $this->materia_id = $param["materia_id"];
		if(isset($param["usuario_id"])) $this->usuario_id = $param["usuario_id"];
		if(isset($param["anio_cursado"])) $this->cursado_anio = $param["anio_cursado"];
		if(isset($param["nota"])) $this->nota = $param["nota"];

		if(isset($param["cursado_id"])) $this->cursado_id = $param["cursado_id"];
		if(isset($param["cursado_nombre"])) $this->cursado_nombre = $param["cursado_nombre"];
		if(isset($param["estado_id"])) $this->estado_id = $param["estado_id"];
		if(isset($param["estado_nombre"])) $this->estado_nombre = $param["estado_nombre"];

		if(isset($param["fecha_inscripcion"])) {
			$this->fecha_inscripcion = $param["fecha_inscripcion"];
		} else {
			$this->fecha_inscripcion = NULL;
		}
		if(isset($param["fecha_modificacion_nota"])) {
			$this->fecha_modificacion_nota = $param["fecha_modificacion_nota"];
		} else {
			$this->fecha_modificacion_nota = NULL;
		}
		if(isset($param["fecha_vencimiento_regularidad"])) {
			$this->fecha_vencimiento_regularidad = $param["fecha_vencimiento_regularidad"];
		} else {
			$this->fecha_vencimiento_regularidad = NULL;
		}
				
		// Database operations 
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idAlumno = ?, idMateria = ?, 
			                                     anio_cursado = ?, idCursado = ?, tipo = ?, fecha_inscripcion = ?,
												 nota = ?, estado_final = ?, fecha_modificacion_nota = ?,
												 fecha_vencimiento_regularidad = ?, idEstado = ?, idUsuario = ?
										     WHERE id = ? ";
			$stmt = $this->conection->prepare($sql);
			try {
				$arr_arg = [$this->alumno_id,$this->materia_id,$this->cursado_anio,$this->cursado_id,$this->cursado_nombre,$this->fecha_inscripcion,$this->nota,
				            $this->estado_nombre,$this->fecha_modificacion_nota,$this->fecha_vencimiento_regularidad,$this->estado_id,$this->usuario_id,$this->id];
			    $stmt->execute($arr_arg);
			} catch (Exception $e) {
				return -1*$e->getCode();
			}	
			$id = $this->id;
		} else {
		
			$sql = "INSERT INTO " . $this->table . 
					" (idAlumno, idMateria, anio_cursado, idCursado, tipo, nota, idEstado, estado_final, 
					   fecha_hora_inscripcion, fecha_modificacion_nota, fecha_vencimiento_regularidad, idUsuario) 
					 values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			try {
		
				$arr_arg = [$this->alumno_id,$this->materia_id,$this->cursado_anio,$this->cursado_id,$this->cursado_nombre,$this->nota, $this->estado_id,$this->estado_nombre,
							$this->fecha_inscripcion,$this->fecha_modificacion_nota,$this->fecha_vencimiento_regularidad,$this->usuario_id];
				
						
				
				$stmt->execute($arr_arg);

				//$stmt->debugDumpParams();
				//var_dump("acm",$arr_arg);exit;	

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
		$sql = "DELETE FROM ".$this->table. " WHERE idAlumno = ? AND idMateria = ? AND anio_cursado = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$alumno_id,$materia_id,$anio]);
	}


	public function getDetallesCursado($materia_id,$arr) {
		$arr_resultado = [];
		//var_dump($arr);die;
		foreach($arr as $value) {
			if ($value['idMateria']==$materia_id) {
				$arr_resultado = $value;
			};
		}
		return $arr_resultado;
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
