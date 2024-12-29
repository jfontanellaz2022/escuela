<?php 
require_once('Db.php');

class AlumnoTipoCursado {

	protected $table = 'tipificacion';
	protected $conection;

	private $id;
	private $codigo; 
	private $nombre; 
	private $descripcion;
	
	protected $cantidad;

	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all Cursados */
	public function getAllAlumnoTipoCursado(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} 


	/* Get Cursado by Id */
	public function getAlumnoTipoCursadoById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	/* Get Cursado by Codigo */
	public function getAlumnoTipoCursadoByCodigo($codigo){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE codigo = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$codigo]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	
	/* Delete Cursado by id */
	public function deleteAlumnoTipoCursadoById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	

}


?>
