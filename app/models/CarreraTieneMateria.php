<?php 
require_once('Db.php');

class CarreraTieneMateria {

	protected $table = 'carrera_tiene_materia';
	protected $conection;
	private $id;
	private $carrera_id;
	private $materia_id;
	private $anio;
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

	/* Get all  */
	public function getAll(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/* Get by Id */
	public function getById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get by Id */
	public function getByIdCarrera($idCarrera){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idCarrera = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idCarrera]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/* Get by Id */
	public function getMateriasByIdCarreraDetalle($idCarrera){
		$this->getConection();
		$sql = "SELECT m.* FROM carrera_tiene_materia ctm, materia m 
		        WHERE ctm.idCarrera = ? and ctm.idMateria=m.id
				ORDER BY anio asc, nombre asc ";
		
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idCarrera]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Get by Id */
	public function materiaPerteneceCarrera($idMateria,$idCarrera){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idMateria = ? AND idCarrera = ? ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idMateria,$idCarrera]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}


	/* Save */
	/*
	public function save($param){
		$this->getConection();

		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			$actualObjeto = $this->getProfesorPerteneceCarreraById($param["id"]);
			if(isset($actualObjeto["id"])){
				$exists = true;	
				//* Actual values 
				$this->id = $param["id"];
				$this->profesor_id = $actualObjeto["idProfesor"];
				$this->carrera_id = $actualObjeto["idCarrera"];
			}
		}

		//* Received values 
		if(isset($param["profesor_id"])) $this->profesor_id = $param["profesor_id"];
		if(isset($param["carrera_id"])) $this->carrera_id = $param["carrera_id"];

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idProfesor=?, idCarrera=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$this->profesor_id,$this->carrera_id, $this->id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (idProfesor, idCarrera) values(?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$this->profesor_id,$this->carrera_id]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}*/

	/* Delete by id */
	public function deleteById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}


	
}


?>
