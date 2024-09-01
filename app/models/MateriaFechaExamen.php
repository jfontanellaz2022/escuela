<?php 
require_once('Db.php');

class MateriaFechaExamen {

	private $table = 'materia_tiene_fechaexamen';
	private $conection;
	private $id;
	private $idCalendarioAcademico;
	private $idMateria;
	private $llamado; 
	private $fechaExamen;

	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all Alumnos */
	public function getMateriasFechasExamenes(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

		/* Get Alumno by Id */
	public function getMateriaFechaExamenById($fecha){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$fecha]);
	
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get Alumno by Id */
	/*public function getMateriaFechaExamenByFecha($fecha){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE fechaExamen like ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} */

	/* Get las fechas de una materia en un calendario */
	public function getMateriaFechaExamenByIdMateriaByIdCalendario($materia_id,$calendario_id){
		$this->getConection();
		$fecha_str = "";
		$arr_resultado = [];
		$sql = "SELECT * FROM " . $this->table . " WHERE idMateria = ? and idCalendarioAcademico = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$materia_id,$calendario_id]);

		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($res as $val) {
			$arr_resultado[] = $val['fechaExamen'];
		}
		$fecha_str = implode(" | ", $arr_resultado);

		return $fecha_str;
	}



/* Save MateriaFechaExamen */
	/*public function save($param){
		$this->getConection();
		
		//* Set default values 
		$id = $idCalendarioAcademico = $idMateria = $llamado = $fechaExamen = "";

		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			
           //die("ID: ".$param["id"]);
			$instancia = $this->getMateriaFechaExamenById($param["id"]);
			//			die('sdfsdfsdf ');

			//var_dump($instancia);die;
			if(isset($instancia["id"])){
				$exists = true;	
				//* Actual values 
				$id = $param["id"];
				$idCalendarioAcademico = $instancia["idCalendarioAcademico"];
				$idMateria = $instancia["idMateria"];
				$llamado = $instancia["llamado"];
				$fechaExamen = $instancia["llamado"];
			}
		}

		//* Received values 
		if(isset($param["calendario_id"])) $idCalendarioAcademico = $param["calendario_id"];
		if(isset($param["materia_id"])) $idMateria = $param["materia_id"];
		if(isset($param["llamado"])) $llamado = $param["llamado"];
		if(isset($param["fecha_examen"])) $fechaExamen = $param["fecha_examen"];
		

		//* Database operations 
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idCalendarioAcademico = ?, idMateria = ?, llamado = ?, fechaExamen = ?  WHERE id = ?";
			//die('entrooo');
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$idCalendarioAcademico,$idMateria,$llamado,"$fechaExamen",$id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (idCalendarioAcademico, idMateria, llamado, fechaExamen) values(?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$idCalendarioAcademico,$idMateria,$llamado,$fechaExamen]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}*/

	/* Delete Alumno by id */
	/*public function deleteMateriaFechaExamenById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}*/

}

// ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR
/*$mat = new MateriaFechaExamen(); 
//var_dump($mat->getMateriaFechaExamenById(30));die;
$arr = ['id'=>30,'calendario_id'=>123, 'materia_id'=>62, 'llamado'=>1, 'fecha_examen'=>'2023-12-12' ];
$mat->save($arr);*/


?>
