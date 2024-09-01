<?php 
require_once('Db.php');

class FechaExamen {

	protected $table = 'materia_tiene_fechaexamen';
	protected $conection;
	private $id;
	private $idCalendarioAcademico;
	private $idMateria;
	private $llamado; 
	private $fechaExamen;
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
	public function getFechasExamanes(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/* Get by Id */
	public function getFechaExamenById($id){
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
			//die('sdfsdfsdf '.$param["habilitado"]);
			$actualObjeto = $this->getFechaExamenById($param["id"]);
			//var_dump($actualAlumno);die;
			if(isset($actualObjeto["id"])){
				$exists = true;	
				//* Actual values 
				$this->id = $param["id"];
				$this->idCalendarioAcademico = $actualObjeto["idCalendarioAcademico"];
				$this->idMateria = $actualObjeto["idMateria"];
				$this->llamado = $actualObjeto["llamado"];
				$this->fechaExamen = $actualObjeto["fechaExamen"];
			}
		}

		//* Received values 
		if(isset($param["idCalendarioAcademico"])) $this->idCalendarioAcademico = $param["idCalendarioAcademico"];
		if(isset($param["idMateria"])) $this->idMateria = $param["idMateria"];
		if(isset($param["llamado"])) $this->llamado = $param["llamado"];
		if(isset($param["fechaExamen"])) $this->fechaExamen = $param["fechaExamen"];

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idCalendarioAcademico=?, idMateria=?, llamado=?, fechaExamen=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$this->idCalendarioAcademico,$this->idMateria,$this->llamado,$this->fechaExamen,$this->id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (idCalendarioAcademico, idMateria, llamado, fechaExamen) values(?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$this->idCalendarioAcademico,$this->idMateria,$this->llamado,$this->fechaExamen]);
			$this->id = $this->conection->lastInsertId();
		}

		return $this->id;	

	}

	/* Delete by id */
	public function delete($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}


	
}

/* ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR*/


?>
