<?php 
require_once('Db.php');

class ProfesorDictaMateria {

	protected $table = 'profesor_dicta_materia';
	protected $conection;
	private $id;
	private $profesor_id;
	private $materia_id;
	private $horas;
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

	/* Get by Id */
	public function getByIdProfesor($profesor_id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idProfesor = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getMateriasByProfesor($profesor_id){
		die('sdsdsd');
		$this->getConection();
		$sql = "SELECT per.id, per.dni, per.apellido, per.nombre, m.id as 'materia_id', m.nombre as 'materia_nombre', m.promocionable, m.anio as 'materia_anio', c.id as 'carrera_id', c.descripcion as 'carrera_nombre', 
					   f.descripcion as 'formato_nombre', cur.descripcion as 'cursado_nombre' 
				FROM profesor_dicta_materia pdm, profesor p, persona per, materia m, carrera_tiene_materia ctm, carrera c, formato f, cursado cur 
				WHERE pdm.idProfesor = ? AND 
      				  pdm.idProfesor = p.id AND
      				  p.idPersona = per.id AND
      				  pdm.idMateria = m.id AND 
	  				  m.id = ctm.idMateria AND ctm.idCarrera = c.id AND 
	  				  m.idFormato = f.id AND m.idCursado = cur.id 
				ORDER BY m.anio ASC, m.nombre ASC;";
		
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id]);
	
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
				//$this->id = $param["id"];
				//$this->profesor_id = $actualObjeto["idProfesor"];
				//$this->carrera_id = $actualObjeto["idCarrera"];
			}
		}
		
		//* Received values 
		if(isset($param["profesor_id"])) $this->profesor_id = $param["profesor_id"];
		if(isset($param["materia_id"])) $this->materia_id = $param["materia_id"];

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idProfesor=?, idMateria=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$this->profesor_id,$this->carrera_id, $this->id]);
		} else {
			
			$sql = "INSERT INTO ".$this->table. " (idProfesor, idMateria) values(?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$this->profesor_id,$this->materia_id]);
			//die('test pdm '.$sql.'   '.$this->profesor_id.' '.$this->materia_id);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}

	/* Delete by id */
	public function deleteById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	public function deleteByProfesorByMateria($id_profesor,$id_materia){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE idProfesor = ? and idMateria = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id_profesor,$id_materia]);
	}


	
}


?>
