<?php 
require_once('ProfesorPerteneceCarrera.php');

class ProfesorPerteneceCarreraDetalle extends  ProfesorPerteneceCarrera {

	/* Get by Id */
	public function getProfesorPerteneceCarreraByIdDetalle($id){
		$this->getConection();
		$sql = "SELECT ppc.id, c.descripcion, c.id as carrera_id, c.habilitada, c.habilitacion_registro, c.imagen, p.id as profesor_id, p.apellido, p.nombre, p.dni 
		        FROM " . $this->table . " ppc ,carrera c, profesor p  
		        WHERE ppc.id = ? and ppc.idProfesor = p.id and ppc.idCarrera = c.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get by Id */
	public function getProfesorPerteneceCarreraByIdProfesorDetalle($profesor_id){
		$this->getConection();
		$sql = "SELECT ppc.id, c.descripcion, c.id as carrera_id, c.habilitada, c.habilitacion_registro, c.imagen, p.id as profesor_id, p.apellido, p.nombre, p.dni 
		        FROM " . $this->table . " ppc ,carrera c, profesor p  
		        WHERE ppc.idProfesor = ? and ppc.idProfesor = p.id and ppc.idCarrera = c.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	
}

//$a = new ProfesorPerteneceCarreraDetalle();

//var_dump($a->getProfesorPerteneceCarreraByIdDetalle(180));

?>
