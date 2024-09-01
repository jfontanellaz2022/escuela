<?php 
require_once('Db.php');

class Materia {

	protected $table = 'materia';
	protected $conection;
	private $id;
	private $nombre; 
	private $anio;
	private $idCursado;
	private $carrera_nombre;
	private $promocionable;
	private $idFormato;
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
	public function getMaterias(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	} 

	/* Get by Id */
	public function getMateriaById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get by Id */
	public function getMateriaNombreById($id){
		$this->getConection();
		$sql = "SELECT nombre FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		//var_dump($res);die;
		//$this->nombre = $res['nombre'];
		return $res['nombre'];
	}

	/* Get Materia by Nombre */
	public function getMateriaByName($nombre){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE nombre like ? ";
		$stmt = $this->conection->prepare($sql);
		$stmt->bindValue(1, "%$nombre%", PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/* Save Alumno */
	public function save($param){
		$this->getConection();

		//* Set default values 
		$id = $nombre = $anio = $cursado_id = $carrera_nombre = $promocionable = $formato_id = "";

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
				$nombre = $actualMateria["nombre"];
				$anio = $actualMateria["anio"];
				$cursado_id = $actualMateria["idCursado"];
				$carrera_nombre = $actualMateria["carrera"];
				$promocionable = $actualMateria["promocionable"];
				$formato_id = $actualMateria["idFormato"];
			}
		}

		//* Received values 
		if(isset($param["nombre"])) $nombre = $param["nombre"];
		if(isset($param["anio"])) $anio = $param["anio"];
		if(isset($param["cursado_id"])) $cursado_id = $param["cursado_id"];
		if(isset($param["carrera_nombre"])) $carrera_nombre = $param["carrera_nombre"];
		if(isset($param["promocionable"])) $promocionable = $param["promocionable"];
		if(isset($param["formato_id"])) $formato_id = $param["formato_id"];

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET nombre=?, anio=?, idCursado=?, carrera=?, promocionable=?, idFormato=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$nombre,$anio,$cursado_id,$carrera_nombre,$promocionable, $formato_id, $id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (nombre, anio, idCursado, carrera, promocionable, idFormato) values(?, ?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$nombre,$anio,$cursado_id,$carrera_nombre,$promocionable, $formato_id]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}

	/* Delete Alumno by id */
	public function deleteMateriaById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

}


// ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR
//$mat = new Materia();
//$mat->save(['id'=>'39','nombre'=>'blablabla','anio'=>'6','cursado_id'=>'3','carrera_nombre'=>'gestion informatica','promocionable'=>'N','idFormato'=>'2']);
//var_dump($mat->getMateriaByName('Ingeni'));


?>
