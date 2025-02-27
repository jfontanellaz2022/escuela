<?php 
require_once "Db.php";
require_once "Constantes.php";

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

	/* Get all Eventos */
	public function getAllEventos($eventos){
		$this->getConection();
		$sql = "SELECT tip.* 
		        FROM (SELECT * FROM tipificacion t WHERE codigo >= 1000 and codigo <= 1023) tip
		        WHERE tip.nombre like ? or tip.codigo like ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute(['%'.$eventos.'%','%'.$eventos.'%']);
        $arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $arr_res;
	} 

	/* Get all Cursados */
	public function getAllAlumnoTipoCursado(){
		$this->getConection();
		$sql = "SELECT * 
		        FROM tipificacion 
		        WHERE codigo = " . Constantes::CODIGO_CURSADO_PRESENCIAL . " or  
				      codigo = " . Constantes::CODIGO_CURSADO_SEMIPRESENCIAL . " or  
					  codigo = " . Constantes::CODIGO_CURSADO_LIBRE . "
			    ORDER BY id ASC";
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

	/* getTipificacionByCodigo by Codigo */
	public function getTipificacionByNombre($nombre){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE nombre = ? ORDER BY id ASC;";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$nombre]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	



}


?>
