<?php 
require_once('Db.php');

class Tipificacion {

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
		$sql = "SELECT * 
		        FROM tipificacion 
		        WHERE codigo = 201 or  codigo = 202 or  codigo = 203 ORDER BY id ASC;";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} 

	/* Get all Estados */
	public function getAllAlumnoEstadosMateria(){
		$this->getConection();
		$arr_resultado = [];
		$sql = "SELECT * 
		        FROM tipificacion 
		        WHERE codigo = '01' or  codigo = '02' or  
				      codigo = '03' or  codigo = '04' or  
					  codigo = '05' or  codigo = '06' 
				ORDER BY id ASC";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
        $arr_resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $arr_resultado;
	} 

	/* Get Cursado by Id */
	public function getAlumnoTipoCursadoById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ? ORDER BY id ASC;";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	/* Get Cursado by Codigo */
	public function getAlumnoTipoCursadoByCodigo($codigo){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE codigo = ? ORDER BY id ASC;";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$codigo]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	
	/* Delete Cursado by id */
	public function deleteAlumnoTipoCursadoById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ? ORDER BY id ASC;";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	

	/* getTipificacionByCodigo by Codigo */
	public function getTipificacionByCodigo($codigo){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE codigo = ? ORDER BY id ASC;";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$codigo]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	



}


?>
