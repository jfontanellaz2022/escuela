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
	private $idPersona;
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
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get by Dni */
	public function getAlumnoByDni($dni){
		//die($dni."*");
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE dni = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$dni]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

/* Get All Alumnos by Carrera */
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
				ORDER BY a.apellido asc, a.nombre asc";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id_carrera]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Save */
	public function save($param){
		$this->getConection();

		//* Set default values 
		$id = $idPersona =0;
		
		$dni = $apellido = $nombres = $anio_ingreso = $debe_titulo = $habilitado = "";

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
				$dni = $actualObjeto["dni"];
				$apellido = $actualObjeto["apellido"];
				$nombres = $actualObjeto["nombre"];
				$anio_ingreso = $actualObjeto["anio_ingreso"];
				$debe_titulo = $actualObjeto["debe_titulo"];
				$habilitado = $actualObjeto["habilitado"];
				$idPersona = $actualObjeto["idPersona"];
			}
		}

		//* Received values 
		if(isset($param["dni"])) $dni = $param["dni"];
		if(isset($param["apellido"])) $apellido = $param["apellido"];
		if(isset($param["nombre"])) $nombres = $param["nombre"];
		if(isset($param["anio_ingreso"])) $anio_ingreso = $param["anio_ingreso"];
		if(isset($param["debe_titulo"])) $debe_titulo = $param["debe_titulo"];
		if(isset($param["habilitado"])) $habilitado = $param["habilitado"];
		if(isset($param["idPersona"])) $idPersona = $param["idPersona"];
		
		//var_dump($param);exit;

		//* Database operations 
		
		if($exists){
			//var_dump([$dni,$apellido,$nombres,$anio_ingreso,$debe_titulo, $habilitado, $idPersona]);exit;
			$sql = "UPDATE ".$this->table. " SET dni=?, apellido=?, nombre=?, anioIngreso=?, debeTitulo=?, habilitado=?, idPersona=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			try {
			$res = $stmt->execute([$dni,$apellido,$nombres,$anio_ingreso,$debe_titulo, $habilitado, $idPersona, $id]);
			} catch (Exception $e) {
				$id = -1;
			}
		} else {
			//var_dump([$dni,$apellido,$nombres,$anio_ingreso,$debe_titulo, $habilitado, $idPersona]);exit;
			$sql = "INSERT INTO ".$this->table. " (dni, apellido, nombre, anioIngreso, debeTitulo, habilitado, idPersona) values(?, ?, ?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			try {
				$stmt->execute([$dni,$apellido,$nombres,$anio_ingreso,$debe_titulo, $habilitado, $idPersona]);
				$id = $this->conection->lastInsertId();
			} catch (Exception $e) {
				$id = -1;
			}
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
