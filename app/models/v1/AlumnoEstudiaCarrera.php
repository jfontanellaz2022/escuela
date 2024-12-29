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
	public function getAlumnoEstudiaCarrera(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	} 

	/* Get Carrera by Id */
	public function getAlumnoEstudiaCarreraById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
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


	/* Save Alumno estudia Carrera */
	public function save($param){
		$this->getConection();
		//* Set default values 
		$id = $alumno_id = $carrera_id = $anio = 0;
		$mesa_especial = $fecha_inscripcion = "";
		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			//die('sdfsdfsdf '.$param["habilitado"]);
			$actualMateria = $this->getMateriaById($param["id"]);
			//var_dump($actualAlumno);die;
			if(isset($actualAlumno["id"])){
				$exists = true;	
				//* Actual values 
				$id = $param["id"];
				$alumno_id = $actualMateria["idAlumno"];
				$carrera_id = $actualMateria["idCarrera"];
				$anio = $actualMateria["anio"];
				$mesa_especial = $actualMateria["mesa_especial"];
				$fecha_inscripcion = $actualMateria["fecha_inscripcion"];
				
			}
		}
	

		//* Received values 
		if(isset($param["idAlumno"])) $alumno_id = $param["idAlumno"];
		if(isset($param["idCarrera"])) $carrera_id = $param["idCarrera"];
		if(isset($param["anio"])) $anio = $param["anio"];
		if(isset($param["mesa_especial"])) $mesa_especial = $param["mesa_especial"];
		if(isset($param["fecha_inscripcion"])) $fecha_inscripcion = $param["fecha_inscripcion"];
		
		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idAlumno=?, idCarrera=?, anio=?, mesa_especial=?, fecha_inscripcion=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			try{
				$res = $stmt->execute([$alumno_id,$carrera_id,$anio,$mesa_especial,$fecha_inscripcion,$id]);
				$id = $this->id;
			} catch (Exception $e) { 
				return -1;
			}
		} else {
			$sql = "INSERT INTO ".$this->table. " (idAlumno, idCarrera, anio, mesa_especial, fecha_inscripcion) values(?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			try{
				$stmt->execute([$alumno_id,$carrera_id,$anio,$mesa_especial,$fecha_inscripcion]);
				$id = $this->conection->lastInsertId();
			} catch (Exception $e) {
                return -1;
			}

			//$stmt->debugDumpParams();
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
