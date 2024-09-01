<?php 
require_once('Persona.php');
class ProfesorNuevo extends Persona {

	protected $table = 'profesor';
	protected $conection;
	private $id;
	//private $dni;
	//private $apellido;
	//private $nombre; 

	/* Get all Alumnos */
	public function getProfesores(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/* Get Alumno by Id */
	public function getProfesorById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get Alumno by Dni */
	public function getProfesorByDni($dni){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE dni = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$dni]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}


	/* Save Profesor */
	public function save($param){
		$this->getConection();

		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			$actualObjeto = $this->getProfesorById($param["id"]);
			if(isset($actualObjeto["id"])){
				$exists = true;	
				//* Actual values 
				$this->id = $param["id"];
				$this->dni = $actualObjeto["dni"];
				$this->apellido = $actualObjeto["apellido"];
				$this->nombre = $actualObjeto["nombres"];
			}
		}

		//* Received values 
		if(isset($param["dni"])) $this->dni = $param["dni"];
		if(isset($param["apellido"])) $this->apellido = $param["apellido"];
		if(isset($param["nombres"])) $this->nombre = $param["nombres"];

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET dni=?, apellido=?, nombre=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$this->dni,$this->apellido,$this->nombre, $this->id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (dni, apellido, nombre) values(?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$this->dni,$this->apellido,$this->nombre]);
			$this->id = $this->conection->lastInsertId();
		}

		return $this->id;	

	}

	/* Delete Alumno by id */
	public function deleteProfesorById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	
}


?>
