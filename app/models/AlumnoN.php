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

	/* Get All Alumnos by Carrera */
	public function getAllAlumnosByCarrera($id_carrera){
		$this->getConection();
		$sql = "SELECT a.id as idAlumno, a.anioIngreso, a.debeTitulo, p.id as idPersona, p.dni, p.apellido, p.nombre, 
		               p.fechaNacimiento, p.nacionalidad, p.idLocalidad, p.domicilio, p.email, p.telefono_caracteristica, p.telefono_numero
				FROM alumno a, alumno_estudia_carrera aec, persona p
		        WHERE aec.idCarrera = ? AND aec.idAlumno = a.id AND a.idPersona = p.id 
				ORDER BY p.apellido ASC, p.nombre ASC";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id_carrera]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

/* ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR*/
$alumno = new Alumno();
//var_dump($alumno->getAlumnos());die;
//var_dump($alumno->getAlumnoById(1997));die;
//var_dump($alumno->getAllMateriasRendidasPorAlumno(646,"Pendiente"));die;
//var_dump($alumno->getAllMateriasCursadasPorAlumno(646));die;

/*
$alumno->save(['id'=>'1785','dni'=>'24912834','apellido'=>'Fontanellaz','nombres'=>'Javier H.','anio_ingreso'=>'2023','debe_titulo'=>'No','habilitado'=>'No']);
var_dump($alumno->getAlumnoByDni(24912834));
*/

?>
