<?php 
require_once('Persona.php');
require_once('Constantes.php');

class Alumno extends Persona{
	private $id;
	private $anio_ingreso;
	private $debe_titulo;
	
	/* Get all */
	public function getAlumnos(){
		$arr_resultado = $arr_alumnos = $arr_datos_persona = [];
		$this->getConection();
		$sql = "SELECT id as idAlumno, anio_ingreso, debe_titulo, idPersona FROM alumno";
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
	public function getById($id){
		$arr_resultado = $arr_alumno = $arr_datos_persona = [];
		$this->getConection();
		$sql = "SELECT per.*, l.id as 'localidad_id', l.nombre as 'localidad_nombre', 
		              l.cp as 'codigo_postal', p.nombre as 'provincia_nombre',
                   a.anio_ingreso, a.debe_titulo, a.id as idAlumno
            FROM alumno a, persona per, localidad l, provincia p 
            WHERE a.id = ? AND 
			      a.idPersona = per.id AND 
			      per.idLocalidad = l.id AND 
			      l.idProvincia = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		$arr_alumno = $stmt->fetch(PDO::FETCH_ASSOC);
		return $arr_alumno;
	}

	/* Get by Id */
	public function getAlumnoById($id){
		$arr_resultado = $arr_alumno = $arr_datos_persona = [];
		$this->getConection();
		$sql = "SELECT id as idAlumno, anio_ingreso, debe_titulo, habilitado, idPersona FROM alumno WHERE id = ?";
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


	/* Get by Id */
	public function getAlumnoByIdPersona($idPersona){
		$arr_resultado = $arr_alumno = $arr_datos_persona = [];
		$this->getConection();
		$sql = "SELECT id as idAlumno, anio_ingreso, debe_titulo, habilitado, idPersona 
		        FROM alumno WHERE idPersona = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idPersona]);
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
		$sql = "SELECT a.id as idAlumno, a.anio_ingreso, a.debe_titulo, acm.anioCursado, acm.tipo as cursado, acm.estado_final,
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
		$sql = "SELECT a.id as idAlumno, a.anio_ingreso, a.debe_titulo, arm.condicion, arm.nota, arm.estado_final,
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
	$sql = "SELECT a.id as idAlumno, a.anio_ingreso, a.debe_titulo, acm.anioCursado, acm.tipo as cursado, acm.estado_final,
                       acm.FechaHoraInscripcion, acm.nota, acm.FechaModificacionNota, p.email, p.telefono_caracteristica, p.telefono_numero,
					   tca.codigo as 'codigo_cursado'
			FROM alumno a, alumno_cursa_materia acm, persona p, tipo_cursado_alumno tca
			WHERE acm.idAlumno = ? AND
					  acm.idAlumno = a.id AND
					  a.idPersona = p.id AND 
					  acm.idTipoCursadoAlumno = tca.id 
			ORDER BY p.apellido ASC, p.nombre ASC";
	
	if ($estado != "") {
		$sql = "SELECT a.id as idAlumno, a.anio_ingreso, a.debe_titulo, acm.anioCursado, acm.tipo as cursado, acm.estado_final,
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
		$sql = "SELECT a.id as idAlumno, a.anio_ingreso, a.debe_titulo, arm.condicion, arm.nota, arm.estado_final,
                       arm.FechaHoraInscripcion, arm.FechaModificacionNota, arm.idMateria, p.email, p.telefono_caracteristica, p.telefono_numero
                FROM alumno a, alumno_rinde_materia arm, persona p 
                WHERE arm.idAlumno = ? AND
                   	  arm.idAlumno = a.id AND
                      a.idPersona = p.id 
				ORDER BY p.apellido ASC, p.nombre ASC";
		
		if ($estado != "") {
			$sql = "SELECT a.id as idAlumno, a.anio_ingreso, a.debe_titulo, arm.condicion, arm.nota, arm.estado_final,
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

		
		$sql = "SELECT a.id, a.anio_ingreso, a.debe_titulo,a.habilitado,
		               p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fecha_nacimiento, p.nacionalidad, p.idLocalidad, p.domicilio,
					   p.email, p.telefono_caracteristica, p.telefono_numero, p.observaciones, 
					   p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por, 
					   aec.anio, l.nombre as localidad_nombre, prov.nombre as provincia_nombre
        		FROM alumno a, persona p, alumno_estudia_carrera aec, localidad l, provincia prov
				WHERE aec.idAlumno = a.id and
					  a.habilitado = 'Si' and 
					  a.idPersona = p.id and 
					  p.idLocalidad = l.id and 
					  l.idProvincia = prov.id ";

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
		
		$sql = "SELECT a.id, a.anio_ingreso, a.debe_titulo,a.habilitado, p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fecha_nacimiento, p.nacionalidad, p.idLocalidad, p.domicilio, p.email, p.telefono_caracteristica, 
					   p.telefono_numero, p.observaciones, p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por, 
					   acm.anio_cursado, acm.tipo as cursado, acm.estado_final, acm.fecha_hora_inscripcion, acm.nota, acm.fecha_modificacion_nota,
					   p.email,  
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

	/* Get All Alumnos by Materia Detalle - ACTUALIZADO */
	public function getAllAlumnosByMateriaDetalle($param){
		$this->getConection();
		$arr_resultados = [];
		$codigo_cursado = $anio = $where_cursado = $where_anio = "";
		if (isset($param['materia_id']) ) {
			if (isset($param['cursado']) && $param['cursado']!=null) {
				if ($param['cursado']==Constantes::CODIGO_CURSADO_PRESENCIAL) {
					$codigo_cursado = Constantes::CODIGO_CURSADO_PRESENCIAL;
				} else if ($param['cursado']==Constantes::CODIGO_CURSADO_SEMIPRESENCIAL) {
					$codigo_cursado = Constantes::CODIGO_CURSADO_SEMIPRESENCIAL;
				} else if ($param['cursado']==Constantes::CODIGO_CURSADO_LIBRE) {
					$codigo_cursado = Constantes::CODIGO_CURSADO_LIBRE;
				} 
				$where_cursado = " AND tca1.codigo = $codigo_cursado";	
			};

			if (isset($param['anio']) && $param['anio']!=null) {
				$anio = $param['anio'];
				$where_anio = " AND acm.anio_cursado = $anio";
			};

			
			$sql = "SELECT a.id, a.anio_ingreso, a.debe_titulo,a.habilitado, p.id as idPersona, p.apellido, p.nombre, p.dni, 
						p.fecha_nacimiento, p.nacionalidad, p.idLocalidad, p.domicilio, p.email, p.telefono_caracteristica, 
						p.telefono_numero, p.observaciones, p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por, 
						acm.anio_cursado, acm.tipo as cursado, acm.estado_final, acm.fecha_hora_inscripcion, acm.nota, acm.fecha_modificacion_nota,
						p.email,  
						tca1.id as 'id_cursado', tca1.codigo as 'codigo_cursado', tca1.nombre as 'nombre_cursado',
						tca2.id as 'id_estado', tca2.codigo as 'codigo_estado', tca2.nombre as 'nombre_estado'	
					FROM alumno a, alumno_cursa_materia acm, persona p, tipificacion tca1, tipificacion tca2
					WHERE acm.idMateria = ? and acm.idAlumno = a.id and 
						a.idPersona = p.id and a.habilitado = 'Si' and 
						acm.idCursado = tca1.id and
						acm.idEstado = tca2.id";
			$sql.= $where_cursado;
			$sql.= $where_anio;
			$sql .= " ORDER BY p.apellido asc, p.nombre asc";
			
			try {
				$stmt = $this->conection->prepare($sql);
				$stmt->execute([$param['materia_id']]);
				$arr_resultados =  $stmt->fetchAll(PDO::FETCH_ASSOC);
			} catch (Exception $e) {
				$arr_resultados = [];
			} finally {
				return $arr_resultados;
			}
		} else return $arr_resultados;
	}


	/* Save */
	public function save($param){
		$this->getConection();

		//* Set default values 
		$id = $anio_ingreso = $debe_titulo = $habilitado = $idPersona = "";

		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			
			$actualObjeto = $this->getAlumnoById($param["id"]);
			if(isset($actualObjeto["idAlumno"])){
				$exists = true;	
				//* Actual values 
				$id = $param["id"];
				$anio_ingreso = $actualObjeto["anio_ingreso"];
				$debe_titulo = $actualObjeto["debe_titulo"];
				$habilitado = $actualObjeto["habilitado"];
			}
			//var_dump($param);exit;
		}
		//* Received values 
		if(isset($param["anio_ingreso"])) $anio_ingreso = $param["anio_ingreso"];
		if(isset($param["debe_titulo"])) $debe_titulo = $param["debe_titulo"];
		if(isset($param["habilitado"])) $habilitado = $param["habilitado"];
		else $habilitado = 'Si';
		if(isset($param["idPersona"])) $idPersona = $param["idPersona"];

		//* Database operations 
		//var_dump($param["id"],$id,$actualObjeto,[$id,$anio_ingreso,$debe_titulo, $habilitado, $idPersona]);exit;
		if($exists){
			$sql = "UPDATE alumno SET anio_ingreso=?, debe_titulo=?, habilitado=? WHERE id=?";
			//var_dump([$anio_ingreso,$debe_titulo, $habilitado, $id]);exit;
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$anio_ingreso,$debe_titulo, $habilitado, $id]);
		} else {
			$sql = "INSERT INTO alumno (anio_ingreso, debe_titulo, habilitado, idPersona) values(?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$anio_ingreso,$debe_titulo, $habilitado,$idPersona]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}
	
	
	/* Delete by id */
	public function deleteAlumnoById($id){
		$this->getConection();
		$sql = "DELETE FROM alumno WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}


	
}

//$obj = new Alumno();
//var_dump($obj->getAllAlumnosByMateriaDetalle(["materia_id"=>410,"anio"=>2020]));


?>
