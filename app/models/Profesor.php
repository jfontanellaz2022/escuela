<?php 
require_once('Db.php');

class Profesor {

	protected $table = 'profesor';
	protected $conection;
	private $id;
	private $dni;
	private $apellido;
	private $nombre; 
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


	/* Get all Carreras por id profesor */
	public function getAllCarrerasByProfesor($profesor_id){
		$this->getConection();
		$sql = "SELECT c.id, c.descripcion, c.habilitada, c.imagen, ppc.idProfesor 
		        FROM profesor p, profesor_pertenece_carrera ppc, carrera c 
		        WHERE p.id = ? and p.id = ppc.idProfesor and ppc.idCarrera = c.id ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Get all Carreras por id profesor */
	/*public function getAllMateriasByProfesor($profesor_id){
		$this->getConection();
		$sql = "SELECT p.id, c.descripcion, c.habilitada, c.imagen, ppc.idProfesor  
		        FROM materia m, profesor_pertenece_carrera ppc, carrera c 
		        WHERE p.id = ? and p.id = ppc.idProfesor and ppc.idCarrera = c.id ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}*/

	public function getMateriasByProfesor($profesor_id){
		$this->getConection();
		$sql = "SELECT per.id, per.dni, per.apellido, per.nombre, m.id as 'materia_id', m.nombre as 'materia_nombre', m.promocionable, m.anio as 'materia_anio', c.id as 'carrera_id', c.descripcion as 'carrera_nombre', 
					   f.descripcion as 'formato_nombre', cur.id as 'cursado_codigo', cur.descripcion as 'cursado_nombre'
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

	/* Get all Materias por id profesor e id carrera*/
	public function getAllMateriasByProfesorByCarrera($profesor_id,$carrera_id){
		require_once('Carrera.php');
		require_once('ProfesorDictaMateria.php');
		$arr_materias_dicta_profesor_en_la_carrera = [];
		$objCarrera = new Carrera();
		$arr_materias_de_la_carrera = $objCarrera->getMateriasPorIdCarrera($carrera_id);

		$objProfesorDictaMateria = new ProfesorDictaMateria();
		$arr_todas_materias_dicta_profesor = $objProfesorDictaMateria->getByIdProfesor($profesor_id);
	
		foreach($arr_todas_materias_dicta_profesor as $item_materias_dicta_profesor) {
			foreach ($arr_materias_de_la_carrera as $item_materia_carrera) {
				if ($item_materias_dicta_profesor['idMateria']==$item_materia_carrera['materia_id']) {
					$arr_materias_dicta_profesor_en_la_carrera[] = $item_materia_carrera;
				}
			}
		}

		return $arr_materias_dicta_profesor_en_la_carrera;
	}

	/* Desvincula all Carreras por id profesor */
	public function desvincularCarrerasByProfesor($profesor_id){
		$this->getConection();
		$sql = "DELETE profesor_pertenece_carrera where idProfesor = ? ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}




	
}


?>
