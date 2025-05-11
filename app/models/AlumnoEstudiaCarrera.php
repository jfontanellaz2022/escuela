<?php 
require_once('Db.php');

class AlumnoEstudiaCarrera {

	private $table = 'alumno_estudia_carrera';
	private $conection;
	private $id;
	private $idAlumno; 
	private $anio;
	private $mesa_especial;
	private $fecha_inscripcion;

	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all Carreras */
	public function getAlumnoEstudiaCarrera($carrera_id=0,$alumno_id=0){
		$this->getConection();
		if ($carrera_id==0 && $alumno_id==0) {
			$sql = "SELECT * FROM ".$this->table;
			$stmt = $this->conection->prepare($sql);
			$stmt->execute();
		} else {
			$sql = "SELECT * FROM ".$this->table ." WHERE idAlumno = ? AND idCarrera = ?";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$alumno_id,$carrera_id]);
			//$stmt->debugDumpParams();

		}

		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		return $res;
	} 


	/* Get Carrera by Id */
	public function getById($id){
		$this->getConection();
		$sql = "SELECT *
				FROM alumno_estudia_carrera
				WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		return $res;
	}

	/* Get Carrera by Id */
	public function getAlumnoEstudiaCarreraById($id){
		$this->getConection();
		//$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$sql = "SELECT distinct a.id, p.dni, p.apellido, p.nombre
				FROM alumno_estudia_carrera aec, alumno a, persona p
				WHERE aec.idCarrera = ? AND
					aec.idAlumno = a.id AND 
					a.idPersona = p.id
				ORDER BY p.apellido asc, p.nombre asc";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	/* Get Carrera by Id */
	public function getAlumnoEstudiaCarreraByIdAlumno($alumno_id){
		$this->getConection();
		$sql = "SELECT c.id, c.descripcion,c.habilitada, c.imagen, a.id as idAlumno
				FROM alumno a, alumno_estudia_carrera aec, carrera c
				WHERE a.id = ? and a.id = aec.idAlumno and aec.idCarrera = c.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


    /* Get Carrera by Id */
	public function hasMesaEspecial($param){
		$this->getConection();
		$res = [];
		if (isset($param['alumno_id']) && isset($param['carrera_id'])) {
		    $alumno_id = $param['alumno_id'];
		    $carrera_id = $param['carrera_id'];
		    $sql = "SELECT mesa_especial
    				FROM alumno_estudia_carrera
    				WHERE idAlumno = ? and idCarrera = ?";
    		$stmt = $this->conection->prepare($sql);
    		$stmt->execute([$alumno_id,$carrera_id]);
    		$res = $stmt->fetch(PDO::FETCH_ASSOC);    
    		return $res["mesa_especial"];
		}
		
		return "No";
	}

	/* Save Alumno */
	public function save($param){
		$this->getConection();

		//* Set default values 
		$id = $idAlumno = $idCarrera = $anio = $mesa_especial = $fecha_inscripcion = "";
		
		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			//die('sdfsdfsdf '.$param["habilitado"]);
			$actualMateria = $this->getById($param["id"]);
			//var_dump($actualAlumno);die;
			if(isset($actualAlumno["id"])){
				$exists = true;	
				//* Actual values 
				$id = $param["id"];
				$idAlumno = $actualMateria["idAlumno"];
				$idCarrera = $actualMateria["idCarrera"];
				$anio = $actualMateria["anio"];
				$mesa_especial = $actualMateria["mesa_especial"];
				$fecha_inscripcion = $actualMateria["fecha_inscripcion"];
			}
		}

		//* Received values 
		if(isset($param["idAlumno"])) $idAlumno = $param["idAlumno"];
		if(isset($param["idCarrera"])) $idCarrera = $param["idCarrera"];
		if(isset($param["anio"])) $anio = $param["anio"];
		if(isset($param["mesa_especial"])) $mesa_especial = $param["mesa_especial"];
		if(isset($param["fecha_inscripcion"])) $fecha_inscripcion = $param["fecha_inscripcion"];

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idAlumno = ?, idCarrera = ?,  anio = ?, mesa_especial = ?, fecha_inscripcion = ? WHERE id = ?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$idAlumno,$idCarrera,$anio,$mesa_especial,$fecha_inscripcion, $id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (idAlumno, idCarrera, anio, mesa_especial, fecha_inscripcion) values(?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			//var_dump([$idAlumno,$idCarrera,$anio,$mesa_especial,$fecha_inscripcion]);exit;
			$stmt->execute([$idAlumno,$idCarrera,$anio,$mesa_especial,$fecha_inscripcion]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}

	/* Delete Alumno by id */
	public function deleteAlumnoEstudiaCarreraById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	

}

// ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR
//$aec = new AlumnoEstudiaCarrera();
//$mat->save(['id'=>'39','nombre'=>'blablabla','anio'=>'6','cursado_id'=>'3','carrera_nombre'=>'gestion informatica','promocionable'=>'N','idFormato'=>'2']);
//var_dump($aec->getAlumnoEstudiaCarreraByIdAlumno(111));


?>
