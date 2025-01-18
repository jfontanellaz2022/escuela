<?php 
require_once('Db.php');

class Alumno {

	protected $table = 'alumno';
	protected $conection;
	private $id;
	private $dni;
	private $apellido;
	private $nombre; 
	private $anio_ingreso;
	private $debe_titulo;
	private $habilitado;
	protected $cantidad;

	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all cantidad */
	public function getCantidad(){
		return $this->cantidad;
	}

	/* Get all */
	public function getAlumnos(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/* Get by Id */
	public function getAlumnoById($id){
		$this->getConection();
		$sql = "SELECT a.id, a.anioIngreso, a.debeTitulo,a.habilitado,
		               p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fechaNacimiento, p.nacionalidad, p.idLocalidad, p.domicilio,
					   p.email, p.telefono_caracteristica, p.telefono_numero, p.observaciones, 
					   p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por 
		        FROM " . $this->table . " a, persona p 
				WHERE a.id = ? and a.idPersona = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get by IdPersona */
	public function getAlumnoByIdPersona($idPersona){
		$this->getConection();
		$sql = "SELECT a.id, a.anioIngreso, a.debeTitulo,a.habilitado,
		               p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fechaNacimiento, p.nacionalidad, p.idLocalidad, p.domicilio,
					   p.email, p.telefono_caracteristica, p.telefono_numero, p.observaciones, 
					   p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por 
		        FROM " . $this->table . " a, persona p 
				WHERE a.idPersona = ? and a.idPersona = p.id ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idPersona]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get by Dni */
	public function getAlumnoByDni($dni){
		$this->getConection();
		$sql = "SELECT a.id, a.anioIngreso, a.debeTitulo,a.habilitado,
		               p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fechaNacimiento, p.nacionalidad, p.idLocalidad, p.domicilio,
					   p.email, p.telefono_caracteristica, p.telefono_numero, p.observaciones, 
					   p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por 
				FROM " . $this->table . " a, persona p 
				WHERE p.dni = ? and a.idPersona = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$dni]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get All Alumnos by Carrera - ACTUALIZADO */
	public function getAllAlumnosByCarrera($id_carrera){
		$this->getConection();
		$sql = "SELECT a.id, a.anioIngreso, a.debeTitulo,a.habilitado,
		               p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fechaNacimiento, p.nacionalidad, p.idLocalidad, p.domicilio,
					   p.email, p.telefono_caracteristica, p.telefono_numero, p.observaciones, 
					   p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por
        		FROM alumno a, persona p, alumno_estudia_carrera aec
				WHERE aec.idCarrera = ? and
					aec.idAlumno = a.id and
					a.habilitado = 'Si' and 
					a.idPersona = p.id
				ORDER BY p.apellido asc, p.nombre asc";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id_carrera]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Get All Alumnos by Materia - ACTUALIZADO */
	public function getAllAlumnosByMateria($id_materia){
		$this->getConection();
		$sql = "SELECT a.id, a.anioIngreso, a.debeTitulo,a.habilitado, p.id as idPersona, p.apellido, p.nombre, p.dni, 
		               p.fechaNacimiento, p.nacionalidad, p.idLocalidad, p.domicilio, p.email, p.telefono_caracteristica, 
					   p.telefono_numero, p.observaciones, p.estado_civil, p.ocupacion, p.titulo, p.titulo_expedido_por, 
					   acm.anio_cursado, acm.tipo as cursado, acm.estado_final, acm.fecha_hora_inscripcion, acm.nota, acm.fecha_modificacion_nota,
					   p.email, p.telefono, 
					   tca1.id as 'id_cursado', tca1.codigo as 'codigo_cursado', tca1.nombre as 'nombre_cursado',
					   tca2.id as 'id_estado', tca2.codigo as 'codigo_estado', tca2.nombre as 'nombre_estado'	
				FROM alumno a, alumno_cursa_materia acm, persona p, tipificacion tca1, tipificacion tca2
				WHERE acm.idMateria = ? and acm.idAlumno = a.id and 
				      a.idPersona = p.id and a.habilitado = 'Si' and 
				      acm.idCursado = tca1.id and
					  acm.idEstado = tca2.id
				ORDER BY p.apellido asc, p.nombre asc";
		
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id_materia]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/* Save */
	public function save($param){
		$this->getConection();

		//* Set default values 
		$id = $dni = $apellido = $nombres = $anio_ingreso = $debe_titulo = $habilitado = "";

		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			$actualObjeto = $this->getAlumnoById($param["id"]);
			if(isset($actualObjeto["id"])){
				$exists = true;	
				//* Actual values 
				$id = $param["id"];
				$dni = $actualObjeto["dni"];
				$apellido = $actualObjeto["apellido"];
				$nombres = $actualObjeto["nombres"];
				$anio_ingreso = $actualObjeto["anio_ingreso"];
				$debe_titulo = $actualObjeto["debe_titulo"];
				$habilitado = $actualObjeto["habilitado"];
			}
		}

		//* Received values 
		if(isset($param["dni"])) $dni = $param["dni"];
		if(isset($param["apellido"])) $apellido = $param["apellido"];
		if(isset($param["nombres"])) $nombres = $param["nombres"];
		if(isset($param["anio_ingreso"])) $anio_ingreso = $param["anio_ingreso"];
		if(isset($param["debe_titulo"])) $debe_titulo = $param["debe_titulo"];
		if(isset($param["habilitado"])) $habilitado = $param["habilitado"];
		

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET dni=?, apellido=?, nombre=?, anioIngreso=?, debeTitulo=?, habilitado=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$dni,$apellido,$nombres,$anio_ingreso,$debe_titulo, $habilitado, $id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (dni, apellido, nombre, anioIngreso, debeTitulo, habilitado) values(?, ?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$dni,$apellido,$nombres,$anio_ingreso,$debe_titulo, $habilitado]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

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
//$alumno = new Alumno();
//var_dump($alumno->getConection());
/*
$alumno->save(['id'=>'1785','dni'=>'24912834','apellido'=>'Fontanellaz','nombres'=>'Javier H.','anio_ingreso'=>'2023','debe_titulo'=>'No','habilitado'=>'No']);
var_dump($alumno->getAlumnoByDni(24912834));
*/

?>
