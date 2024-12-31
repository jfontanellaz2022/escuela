<?php 
require_once('Persona.php');

class Alumno extends Persona{
	private $id;
	private $anio_ingreso;
	private $debe_titulo;
	
	/* Get all */
	public function getAlumnos(){
		$arr_resultado = $arr_alumnos = $arr_datos_persona = [];
		$this->getConection();
		$sql = "SELECT id as idAlumno, anioIngreso, debeTitulo, idPersona FROM alumno";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		$arr_alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC); 
		foreach ($arr_alumnos as $alumno) {
			$persona_id = $alumno['idPersona'];
			if ($persona_id!=0) {
			$arr_datos_persona = $this->getPersonaById($persona_id);
			$arr_resultado[] = array_merge($alumno, $arr_datos_persona);}
		}
		
		return $arr_resultado;
	}

	/* Get by Id */
	public function getAlumnoById($id){
		$arr_resultado = $arr_alumno = $arr_datos_persona = [];
		$this->getConection();
		$sql = "SELECT id as idAlumno, anioIngreso, debeTitulo, idPersona FROM alumno WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		$arr_alumno = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($arr_alumno)) {
			$persona_id = $arr_alumno['idPersona'];
			$arr_datos_persona = $this->getPersonaById($persona_id);
			$arr_resultado = array_merge($arr_alumno, $arr_datos_persona);
		}
		return $arr_resultado;
	}


	/* Get All Alumnos que cursaron una Materia */
	public function getAllAlumnosCursanMateria($id_materia){
		$this->getConection();
		$sql = "SELECT a.id as idAlumno, a.anioIngreso, a.debeTitulo, acm.anioCursado, acm.tipo as cursado, acm.estado_final,
                       acm.FechaHoraInscripcion, acm.nota, acm.FechaModificacionNota, p.email, p.telefono_caracteristica, p.telefono_numero,
					   tca.codigo as 'codigo_cursado'
                FROM alumno a, alumno_cursa_materia acm, persona p, tipo_cursado_alumno tca
                WHERE acm.idMateria = ? and
                      acm.idAlumno = a.id and
                      a.idPersona = p.id and
                      acm.idTipoCursadoAlumno = tca.id
				ORDER BY p.apellido ASC, p.nombre ASC";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id_materia]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/* Get All Alumnos que rindieron una Materia*/
	public function getAllAlumnosRindenMateria($id_materia){
		$this->getConection();
		$sql = "SELECT a.id as idAlumno, a.anioIngreso, a.debeTitulo, arm.condicion, arm.nota, arm.estado_final,
                       arm.FechaHoraInscripcion, arm.FechaModificacionNota, p.email, p.telefono_caracteristica, p.telefono_numero
                FROM alumno a, alumno_rinde_materia arm, persona p 
                WHERE arm.idMateria = ? AND
                      arm.idAlumno = a.id AND
                      a.idPersona = p.id
				ORDER BY p.apellido ASC, p.nombre ASC;";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id_materia]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}



/* Get All Alumnos que rindieron una Materia*/
public function getAllMateriasCursadasPorAlumno($id_alumno, $estado = ""){
	$this->getConection();
	$arr_parametros = [$id_alumno];
	$sql = "SELECT a.id as idAlumno, a.anioIngreso, a.debeTitulo, acm.anioCursado, acm.tipo as cursado, acm.estado_final,
                       acm.FechaHoraInscripcion, acm.nota, acm.FechaModificacionNota, p.email, p.telefono_caracteristica, p.telefono_numero,
					   tca.codigo as 'codigo_cursado'
			FROM alumno a, alumno_cursa_materia acm, persona p, tipo_cursado_alumno tca
			WHERE acm.idAlumno = ? AND
					  acm.idAlumno = a.id AND
					  a.idPersona = p.id AND 
					  acm.idTipoCursadoAlumno = tca.id 
			ORDER BY p.apellido ASC, p.nombre ASC";
	
	if ($estado != "") {
		$sql = "SELECT a.id as idAlumno, a.anioIngreso, a.debeTitulo, acm.anioCursado, acm.tipo as cursado, acm.estado_final,
                       acm.FechaHoraInscripcion, acm.nota, acm.FechaModificacionNota, p.email, p.telefono_caracteristica, p.telefono_numero,
					   tca.codigo as 'codigo_cursado'
				FROM alumno a, alumno_cursa_materia acm, persona p, tipo_cursado_alumno tca
				WHERE acm.idAlumno = ? AND
					  acm.idAlumno = a.id AND
					  a.idPersona = p.id AND 
					  acm.idTipoCursadoAlumno = tca.id AND 
					  acm.estado_final = ? 
				ORDER BY p.apellido ASC, p.nombre ASC ";
		$arr_parametros[] = $estado;			
	}

	$stmt = $this->conection->prepare($sql);
	$stmt->execute($arr_parametros);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




	/* Get All Alumnos que rindieron una Materia*/
	public function getAllMateriasRendidasPorAlumno($id_alumno, $estado = ""){
		$this->getConection();
		$arr_parametros = [$id_alumno];
		$sql = "SELECT a.id as idAlumno, a.anioIngreso, a.debeTitulo, arm.condicion, arm.nota, arm.estado_final,
                       arm.FechaHoraInscripcion, arm.FechaModificacionNota, arm.idMateria, p.email, p.telefono_caracteristica, p.telefono_numero
                FROM alumno a, alumno_rinde_materia arm, persona p 
                WHERE arm.idAlumno = ? AND
                   	  arm.idAlumno = a.id AND
                      a.idPersona = p.id 
				ORDER BY p.apellido ASC, p.nombre ASC";
		
		if ($estado != "") {
			$sql = "SELECT a.id as idAlumno, a.anioIngreso, a.debeTitulo, arm.condicion, arm.nota, arm.estado_final,
                       arm.FechaHoraInscripcion, arm.FechaModificacionNota, arm.idMateria, p.email, p.telefono_caracteristica, p.telefono_numero
                	FROM alumno a, alumno_rinde_materia arm, persona p 
                	WHERE arm.idAlumno = ? AND
                    	  arm.idAlumno = a.id AND
                      	a.idPersona = p.id AND arm.estado_final = ? 
					ORDER BY p.apellido ASC, p.nombre ASC";
			$arr_parametros[] = $estado;			
		}

		$stmt = $this->conection->prepare($sql);
		$stmt->execute($arr_parametros);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/* Get All Alumnos by Carrera - ACTUALIZADO */
	public function getAllAlumnosByCarrera($param){
		$this->getConection();

		
		$sql = "SELECT a.id, a.anioIngreso, a.debeTitulo,a.habilitado,
		               p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fechaNacimiento, p.nacionalidad, p.idLocalidad, p.domicilio,
					   p.email, p.telefono_caracteristica, p.telefono_numero, p.observaciones, 
					   p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por, 
					   aec.anio, l.nombre as localidad_nombre, prov.nombre as provincia_nombre
        		FROM alumno a, persona p, alumno_estudia_carrera aec, localidad l, provincia prov
				WHERE aec.idAlumno = a.id and
					  a.habilitado = 'Si' and 
					  a.idPersona = p.id and 
					  p.idLocalidad = l.id and 
					  l.provincia_id = prov.id ";

		if (isset($param['carrera_id'])) {
					
		   $sql .= " and aec.idCarrera = " . $param['carrera_id'];

		};
		if ($param['anio']) {

			$sql .= " and aec.anio = " . $param['anio'];

		};

		$sql .= " ORDER BY p.apellido asc, p.nombre asc ";

		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	
	/* Get All Alumnos by Materia - ACTUALIZADO */
	public function getAllAlumnosByMateria($id_materia){
		$this->getConection();
		
		$sql = "SELECT a.id, a.anioIngreso, a.debeTitulo,a.habilitado, p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fechaNacimiento, p.nacionalidad, p.idLocalidad, p.domicilio, p.email, p.telefono_caracteristica, 
					   p.telefono_numero, p.observaciones, p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por, 
					   acm.anio_cursado, acm.tipo as cursado, acm.estado_final, acm.fecha_inscripcion, acm.nota, acm.fecha_modificacion_nota,
					   p.email, p.telefono, 
					   tca1.id as 'id_cursado', tca1.codigo as 'codigo_cursado', tca1.nombre as 'nombre_cursado',
					   tca2.id as 'id_estado', tca2.codigo as 'codigo_estado', tca2.nombre as 'nombre_estado'	
				FROM alumno a, alumno_cursa_materia acm, persona p, tipificacion tca1, tipificacion tca2
				WHERE acm.idMateria = ? and acm.idAlumno = a.id and 
				      a.idPersona = p.id and a.habilitado = 'Si' and 
				      acm.idCursado = tca1.id and
					  acm.idEstado = tca2.id
				ORDER BY p.apellido asc, p.nombre asc";
		//var_dump($sql);exit;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id_materia]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/* Save */
	public function save($param){
		$this->getConection();

		//* Set default values 
		$id = $anio_ingreso = $debe_titulo = $habilitado = "";

		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			//die('sdfsdfsdf '.$param["habilitado"]);
			$actualObjeto = $this->getAlumnoById($param["id"]);
			//var_dump($actualAlumno);die;
			if(isset($actualObjeto["id"])){
				$exists = true;	
				//* Actual values 
				$id = $param["id"];
				$anio_ingreso = $actualObjeto["anio_ingreso"];
				$debe_titulo = $actualObjeto["debe_titulo"];
				$habilitado = $actualObjeto["habilitado"];
			}
		}

		//* Received values 
		if(isset($param["anio_ingreso"])) $anio_ingreso = $param["anio_ingreso"];
		if(isset($param["debe_titulo"])) $debe_titulo = $param["debe_titulo"];
		if(isset($param["habilitado"])) $habilitado = $param["habilitado"];
		

		//* Database operations 
		
		if($exists){
			if($exists){
			$sql = "UPDATE ".$this->table. " SET anioIngreso=?, debeTitulo=?, habilitado=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$dni,$apellido,$nombres,$anio_ingreso,$debe_titulo, $habilitado, $id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (anioIngreso, debeTitulo, habilitado) values(?, ?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$anio_ingreso,$debe_titulo, $habilitado]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}

	}
	
	
	/* Delete by id */
	public function deleteAlumnoById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}


	
}

?>
