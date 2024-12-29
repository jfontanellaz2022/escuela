<?php 
require_once('Db.php');

class ProfesorPerteneceCarrera {

	protected $table = 'profesor_pertenece_carrera';
	protected $conection;
	private $id;
	private $profesor_id;
	private $carrera_id;
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
	public function getProfesorPerteneceCarrera(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/* Get by Id */
	public function getProfesorPerteneceCarreraById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Save */
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

	}

	/* Delete Alumno by id */
	public function deleteProfesorPerteneceCarreraById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	/* Delete Profesor/Carrera por id o idProfesor e idCarrera */
	public function deleteProfesorPerteneceCarrera($param){
		$this->getConection();
		$id = $profesor_id = $carrera_id = 0;

		if (isset($param['id'])) {
			$id = $param['id'];
			$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
			$stmt = $this->conection->prepare($sql);
			return $stmt->execute([$id]);
		} else if (isset($param['profesor_id'])&&isset($param['carrera_id'])) {
			$profesor_id = $param['profesor_id'];
			$carrera_id = $param['carrera_id'];
			$sql = "DELETE FROM ".$this->table. " WHERE idProfesor = ? and idCarrera = ?";
			$stmt = $this->conection->prepare($sql);
			return $stmt->execute([$profesor_id,$carrera_id]);
		} else {
			return -1; 
		}
		
	}


	
}


?>
