<?php 
require_once('Db.php');

class MateriaFechaExamen {

	private $table = 'materia_fecha_examen';
	private $id;
	private $idCalendario;
	private $idMateria;
	private $llamado; 
	private $fecha_examen;
	protected $conection;
	protected $cantidad;

	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	public function getCantidad(){
		return $this->cantidad;
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
	public function getMateriaFechaExamenById($id){
		$this->getConection();
		$sql = "SELECT mtf.id as 'fecha_examen_id', 
		               mtf.idCalendario as 'calendario_id', 
					   mtf.idMateria as 'materia_id', m.nombre as 'materia_nombre', 
					   mtf.llamado, mtf.fecha_examen as 'fecha_examen'
				FROM materia_fecha_examen mtf, materia m 
				WHERE mtf.id = ? and mtf.idMateria = m.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
	
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
		$sql = "SELECT * FROM " . $this->table . " WHERE idMateria = ? and idCalendario = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$materia_id,$calendario_id]);

		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($res as $val) {
			$arr_resultado[] = $val['fecha_examen'];
		}
		$fecha_str = implode(" | ", $arr_resultado);

		return $fecha_str;
	}

/* Save */
public function save($param){
	$this->getConection();

	//* Check if exists 
	$exists = false;
	if(isset($param["id"]) and $param["id"] !=''){
	
		$actualObjeto = $this->getMateriaFechaExamenById($param["id"]);
		if(isset($actualObjeto["fecha_examen_id"])){
			$exists = true;	
			//* Actual values 
			$this->id = $param["id"];
			$this->idCalendario = $actualObjeto["calendario_id"];
			$this->idMateria = $actualObjeto["materia_id"];
			$this->llamado = $actualObjeto["llamado"];
			$this->fecha_examen = $actualObjeto["fecha_examen"];
		}
	}

	//* Received values 
	if(isset($param["idCalendario"])) $this->idCalendario = $param["idCalendario"];
	if(isset($param["idMateria"])) $this->idMateria = $param["idMateria"];
	if(isset($param["llamado"])) $this->llamado = $param["llamado"];
	if(isset($param["fecha_examen"])) $this->fecha_examen = $param["fecha_examen"];
	//var_dump("hola",$param);exit;
	//* Database operations 
	$code = 0;
	if($exists){
		$sql = "UPDATE ".$this->table. " SET idCalendario=?, idMateria=?, llamado=?, fecha_examen=? WHERE id=?";
		try {
			$stmt = $this->conection->prepare($sql);
			//var_dump([$this->idCalendario,$this->idMateria,$this->llamado,$this->fecha_examen,$this->id]);exit;
			$res = $stmt->execute([$this->idCalendario,$this->idMateria,$this->llamado,$this->fecha_examen,$this->id]);
			$code = $this->id;
		} catch (Exception $e){
			$code = -1;
		}
	} else {
		//die('entro insert');
		//var_dump([$this->idCalendario,$this->idMateria,$this->llamado,$this->fecha_examen]);exit;
		$sql = "INSERT INTO ".$this->table. " (idCalendario, idMateria, llamado, fecha_examen) values(?, ?, ?, ?)";
		try {
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$this->idCalendario,$this->idMateria,$this->llamado,$this->fecha_examen]);
			$code = $this->id = $this->conection->lastInsertId();
		} catch (Exception $e){
			$code = -1;
		}
	}

	return $code;	

}

public function delete($id) {
	$this->getConection();
	$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
	$stmt = $this->conection->prepare($sql);
	return $stmt->execute([$id]);
}



}

// ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR
/*$mat = new MateriaFechaExamen(); 
//var_dump($mat->getMateriaFechaExamenById(30));die;
$arr = ['id'=>30,'calendario_id'=>123, 'materia_id'=>62, 'llamado'=>1, 'fecha_examen'=>'2023-12-12' ];
$mat->save($arr);*/


?>
