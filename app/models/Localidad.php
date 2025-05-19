<?php 
require_once('Db.php');

class Localidad {

	protected $table = 'localidad';
	protected $conection;

	private $id;
	private $nombre; 
	private $cp;
	private $idProvincia;
	protected $cantidad;

	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all Localidades */
	public function getLocalidades(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} 

	/* Get Localidad by Id */
	public function getById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get Localidad by Nombre */
	public function getByName($nombre){
		$this->getConection();
		$sql = "SELECT l.id, l.nombre as 'localidad_nombre', l.cp, p.nombre as 'provincia_nombre' 
		        FROM localidad l, provincia p 
				WHERE l.nombre like ? AND l.idProvincia=p.id ORDER BY l.nombre ASC";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute(["%".$nombre."%"]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}


?>
