<?php 
require_once('ProfesorDictaMateria.php');

class ProfesorDictaMateriaDetalle  extends ProfesorDictaMateria{


/* USADO POR:
   PERFIL: PROFESOR
   PROCESO: GESTION DE EXAMENES FINALES
   
   FINALIDAD: SACA TODAS LAS MATERIAS QUE DICTA EL PROFESOR.

*/


public function getMateriasByIdProfesor($profesor_id){
	$this->getConection();
	$sql = "SELECT per.id, per.dni, per.apellido, per.nombre, m.id as 'materia_id', m.nombre as 'materia_nombre', m.promocionable, 
	               m.anio as 'materia_anio', c.id as 'carrera_id', c.descripcion as 'carrera_nombre', 
				   t.nombre as 'formato_nombre'
			FROM profesor_dicta_materia pdm, profesor p, materia m, carrera_tiene_materia ctm, carrera c, tipificacion t, persona per
			WHERE pdm.idProfesor = ? AND 
				pdm.idProfesor = p.id AND
				p.idPersona = per.id AND
				pdm.idMateria = m.id AND 
				m.id = ctm.idMateria AND 
				ctm.idCarrera = c.id AND 
				m.idFormato = t.id  
			ORDER BY m.anio ASC, m.nombre ASC;";
	$stmt = $this->conection->prepare($sql);
	$stmt->execute([$profesor_id]);

	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getProfesorByIdMateria($materia_id){
	$this->getConection();
	$sql = "SELECT p.id, p.dni, p.apellido, p.nombre, p.email, m.id as 'materia_id', m.nombre as 'materia_nombre', 
	               m.promocionable, m.anio as 'materia_anio' FROM profesor_dicta_materia pdm, profesor pro, 
				   materia m, persona p 
			WHERE pdm.idMateria = ? AND pdm.idProfesor = pro.id AND 
			      pro.idPersona=p.id AND pdm.idMateria = m.id 
			ORDER BY m.anio ASC, m.nombre ASC;";
	$stmt = $this->conection->prepare($sql);
	$stmt->execute([$materia_id]);

	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
	


	
}


?>
