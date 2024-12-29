<?php 
require_once('Db.php');

class Evento {

	protected $table = 'evento';
	protected $conection;
	private $id;
	private $codigo;
	private $descripcion;
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

	/* Get by codigo */
	public function getByCodigo($codigo){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE codigo = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$codigo]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}


	/* Save */
	public function save($param){
		$this->getConection();

		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			//die('sdfsdfsdf '.$param["habilitado"]);
			$actualObjeto = $this->getById($param["id"]);
			//var_dump($actualAlumno);die;
			if(isset($actualObjeto["id"])){
				$exists = true;	
				//* Actual values 
				$this->id = $param["id"];
				$this->codigo = $actualObjeto["codigo"];
				$this->descripcion = $actualObjeto["descripcion"];
			}
		}

		//* Received values 
		if(isset($param["codigo"])) $this->codigo = $param["codigo"];
		if(isset($param["descripcion"])) $this->descripcion = $param["descripcion"];
				

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET codigo=?, descripcion=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$this->descripcion, $this->codigo, $this->id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (codigo, descripcion) Values (?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$this->descripcion, $this->codigo]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

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
//$alumno = new Alumno();
//var_dump($alumno->getConection());
/*
$alumno->save(['id'=>'1785','dni'=>'24912834','apellido'=>'Fontanellaz','nombres'=>'Javier H.','anio_ingreso'=>'2023','debe_titulo'=>'No','habilitado'=>'No']);
var_dump($alumno->getAlumnoByDni(24912834));
*/

?>
