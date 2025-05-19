<?php 
require_once('AlumnoRindeMateria.php');

class AlumnoRindeMateriaDetalle extends AlumnoRindeMateria{

	/* Get Alumnos by Id Materia y por Id Calendario */
	public function getAlumnosByIdMateriaByIdCalendario($materia_id,$calendario_id,$llamado=3){
		$this->getConection();
		if ($llamado==3) {
			$sql = "SELECT * FROM " . $this->table . " WHERE idMateria = ? and idCalendario = ?";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$materia_id,$calendario_id]);
		} else {
			$sql = "SELECT * FROM " . $this->table . " WHERE idMateria = ? and idCalendario = ? and llamado = ?";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$materia_id,$calendario_id,$llamado]);
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Get Alumnos by Id Materia y por Id Calendario */
	public function getAlumnosByIdMateriaByIdCalendarioDetalle($materia_id,$calendario_id,$llamado=3){
		$this->getConection();
		if ($llamado==3) {
			$sql = "SELECT a.id, a.anio_ingreso, a.debe_titulo, a.habilitado, a.idPersona,
			               p.dni, p.apellido, p.nombre, p.email, p.telefono_caracteristica, p.telefono_numero,
			               arm.condicion, arm.nota, arm.estado_final, arm.FechaHoraInscripcion 
			        FROM alumno_rinde_materia arm, alumno a, persona p  
					WHERE arm.idMateria = ? and arm.idCalendario = ? and arm.idAlumno = a.id and a.idPersona = p.id
					ORDER BY p.apellido ASC, p.nombre ASC";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$materia_id,$calendario_id]);
		} else {
			$sql = "SELECT a.id,a.anio_ingreso, a.debe_titulo, a.habilitado, a.idPersona,
			               p.dni, p.apellido, p.nombre, p.email, p.telefono_caracteristica, p.telefono_numero,
			               arm.condicion, arm.nota, arm.estado_final, arm.FechaHoraInscripcion 
					FROM alumno_rinde_materia arm, alumno a, persona p 
					WHERE arm.idMateria = ? and arm.idCalendario = ? and arm.llamado = ? and arm.idAlumno=a.id and a.idPersona = p.id
					ORDER BY p.apellido ASC, p.nombre ASC";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$materia_id,$calendario_id,$llamado]);
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}



}

?>
