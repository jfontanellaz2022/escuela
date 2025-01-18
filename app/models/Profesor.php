<?php 
require_once('Db.php');

class Profesor {

	protected $table = 'profesor';
	protected $conection;
	private $id;
	private $idPersona;
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

	/* Get by Id */
	public function getById($id){
		$arr_resultado = $arr_alumno = [];
		$this->getConection();
		$sql = "SELECT pr.idPersona, per.*, l.id as 'localidad_id', l.nombre as 'localidad_nombre', 
		              l.cp as 'codigo_postal', p.nombre as 'provincia_nombre'
            FROM profesor pr, persona per, localidad l, provincia p 
            WHERE pr.id = ? AND 
			      pr.idPersona = per.id AND 
			      per.idLocalidad = l.id AND 
			      l.idProvincia = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		$arr_profesor = $stmt->fetch(PDO::FETCH_ASSOC);
		return $arr_profesor;
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
	public function getProfesorByIdPersona($idPersona){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idPersona = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idPersona]);

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
				$this->idPersona = $actualObjeto["idPersona"];
			}
		}

		//* Received values 
		if(isset($param["idPersona"])) $this->idPersona = $param["idPersona"];

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idPersona = ? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$this->idPersona,$this->id]);
			//$stmt->debugDumpParams();

		} else {
			$sql = "INSERT INTO ".$this->table. " (idPersona) values(?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$this->idPersona]);
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

	
   // ********************* ACTUALIZADO ************************ /
	public function getMateriasByProfesor($profesor_id){
		$this->getConection();
		$sql = "SELECT per.id, per.dni, per.apellido, per.nombre, m.id as 'materia_id', m.nombre as 'materia_nombre', 
		               m.promocionable, m.anio as 'materia_anio', c.id as 'carrera_id', c.descripcion as 'carrera_nombre', 
					   formato.id as 'formato_id', formato.codigo as 'formato_codigo', formato.nombre as 'formato_nombre', 
					   cursado.id as 'cursado_id', cursado.codigo as 'cursado_codigo', cursado.nombre as 'cursado_nombre' 
			    FROM profesor_dicta_materia pdm, profesor p, persona per, materia m, carrera_tiene_materia ctm, carrera c, 
				     tipificacion formato, tipificacion cursado
				WHERE pdm.idProfesor = ? AND pdm.idProfesor = p.id AND p.idPersona = per.id AND 
				      pdm.idMateria = m.id AND m.id = ctm.idMateria AND ctm.idCarrera = c.id AND 
					  m.idFormato = formato.id AND m.idCursado = cursado.id ORDER BY m.anio ASC, m.nombre ASC";
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
