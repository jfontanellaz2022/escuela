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

	/* Get by Id */
	public function getProfesorPerteneceCarreraByIdProfesor($idProfesor){
		$this->getConection();
		$sql = "SELECT c.descripcion, c.habilitada, c.imagen, ppc.idProfesor, ppc.idCarrera
				FROM profesor_pertenece_carrera ppc, carrera c
				WHERE ppc.idProfesor = ? and ppc.idCarrera = c.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idProfesor]);
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		//var_dump($res);exit;

		return $res;
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
			//var_dump($sql);			
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$this->profesor_id,$this->carrera_id, $this->id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (idProfesor, idCarrera) values(?, ?)";
			//var_dump([$this->profesor_id,$this->carrera_id],$sql);exit;
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$this->profesor_id,$this->carrera_id]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}

	/* Delete Vinculo entre Profesor y carrera by id */
	public function deleteProfesorPerteneceCarreraById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	/* Delete Vinculo entre Profesor y carrera by id */
	public function deleteProfesorPerteneceCarreraByProfesor($id_profesor){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE idProfesor = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id_profesor]);
	}

	/* Delete Vinculo entre Profesor y carrera by id */
	public function deleteProfesorPerteneceCarreraByProfesorByCarrera($id_profesor,$id_carrera){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE idProfesor = ? and idCarrera = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id_profesor,$id_carrera]);
	}



	
}


?>
