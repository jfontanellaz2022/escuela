<?php 
require_once('Db.php');

class Carrera {

	protected $table = 'carrera';
	protected $conection;

	private $id;
	private $codigo; 
	private $descripcion;
	private $descripcion_corta;
	private $habilitada;
	private $habilitacion_registro;
	private $imagen;

	protected $cantidad;

	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all Carreras */
	public function getCarreras(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} 

	/* Get Carrera by Id */
	public function getCarreraById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get Materia by Nombre */
	public function getCarreraByName($nombre){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE descripcion like ? ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute(["%$nombre%"]);
		//$stmt->debugDumpParams();exit;
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	/* Get Carrera habilitadas para la inscripcion del nuevo a���o */
	public function getCarrerasHabilitadasRegistracion(){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE habilitacion_registro = 'Si' ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		$arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		//var_dump($arr_res);exit;
		return $arr_res;
	}

	/* Get Carreras habilitadas  */
	public function getCarrerasHabilitadas(){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE habilitada = 'Si' ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		$arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $arr_res;
	}

	/* Save Alumno */
	/*public function save($param){
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

	}*/

	/* Delete Alumno by id */
	public function deleteCarreraById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}


	public function getMateriaPorIdCarrera($carrera_id)
    {
		$this->getConection();
        $arr_resultados = [];
        $sql = "SELECT idMateria
                FROM carrera c, carrera_tiene_materia ctm
                WHERE c.id = ? AND c.id = ctm.idCarrera";
				
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$carrera_id]);

		foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
			$arr_resultados[] = $fila['idMateria'];
		}

        return $arr_resultados;
    }

	public function getMateriasPorIdCarrera($carrera_id)
    {
		$this->getConection();
        $arr_resultados = [];
        $sql = "SELECT m.*, ctm.idCarrera
                FROM carrera_tiene_materia ctm, materia m 
                WHERE ctm.idCarrera = ? AND m.id = ctm.idMateria ";
        //die($sql);				
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$carrera_id]);
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($res as $fila) {
			$arr_fila = [];
			$arr_fila['materia_id'] = $fila['id'];
			$arr_fila['nombre'] = $fila['nombre'];
			$arr_fila['anio'] = $fila['anio'];
			$arr_fila['cursado_id'] = $fila['idCursado'];
			$arr_fila['carrera'] = $fila['carrera'];
			$arr_fila['promocionable'] = $fila['promocionable'];
			$arr_fila['formato_id'] = $fila['idFormato'];
			$arr_fila['carrera_id'] = $fila['idCarrera'];
			$arr_resultados[] = $arr_fila;

		}
        return $arr_resultados;
    }

	public function getCantidadAniosCarrera($carrera_id)
    {
		$this->getConection();
        $val = 0;
        $sql = "SELECT max(m.anio) as anio_max
                FROM carrera_tiene_materia ctm, materia m 
                WHERE ctm.idCarrera = ? AND m.id = ctm.idMateria ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$carrera_id]);

		$res = $stmt->fetch(PDO::FETCH_ASSOC);
        $val = $res['anio_max'];
        return $val;
    }
	

}




?>
