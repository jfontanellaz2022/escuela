<?php 
require_once('Db.php');
require_once('AlumnoRindeMateria.php');

class AlumnoRindeMateriaRepair {

	


	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all Alumnos */
	public function getMateriasCursadasPorAlumno($idAlumno,$idMateria){
			$this->getConection();
			$sql = "SELECT * FROM alumno_cursa_materia WHERE idAlumno = ? and idMateria = ?
			        ORDER BY anio_cursado DESC LIMIT 1";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$idAlumno,$idMateria]);
			return $stmt->fetch(PDO::FETCH_ASSOC);
	
		
	}


	public function getInscripciones($idCalendario){
		$this->getConection();
		$sql = "SELECT * FROM alumno_rinde_materia WHERE idCalendario = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idCalendario]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function ActualizarInscripciones($idCalendario){
		$objInscripcion = new AlumnoRindeMateria();
		$arr_inscripciones = $this->getInscripciones($idCalendario);
		$param = [];
		foreach ($arr_inscripciones as $value) {
			$param = [];
			$condicion = "";
			if ($this->getMateriasCursadasPorAlumno($value['idAlumno'],$value['idMateria'])['estado_final']=="Regularizo") {
				$condicion = "Regular";
			} else if ($this->getMateriasCursadasPorAlumno($value['idAlumno'],$value['idMateria'])['estado_final']=="Libre") {
				$condicion = "Libre";
			} else if ($this->getMateriasCursadasPorAlumno($value['idAlumno'],$value['idMateria'])['estado_final']=="Promociono") {
				$condicion = "Promocion";
			} else if ($this->getMateriasCursadasPorAlumno($value['idAlumno'],$value['idMateria'])['estado_final']=="Aprobo") {
				$condicion = "Promocion";
			}

			$param["id"] = $value['id'];
			$param["alumno_id"] = $value['idAlumno'];
			$param["materia_id"] = $value['idMateria'];
			$param["calendario_id"] = $value['idCalendario'];
			$param["llamado"] = $value['llamado'];
			$param["nota"] = $value['nota'];

			$param["condicion"] = $condicion;

			$param["estado_final"] = $value['estado_final'];
			$param["fecha_hora_inscripcion"] = $value['FechaHoraInscripcion'];
			$param["fecha_modificacion_nota"] = $value['FechaModificacionNota'];
			$param["usuario_id"] = $value['idUsuario'];
			$objInscripcion->save($param);
		}
		
		
		//var_dump($param);exit;
		
	}


}

// ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR

//$arm = new AlumnoRindeMateriaRepair();
//var_dump($arm->getMateriasCursadasPorAlumno(1892,396));
//$arm->ActualizarInscripciones(167);

?>
