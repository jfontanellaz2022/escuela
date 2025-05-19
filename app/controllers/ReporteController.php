<?php 
define('ROOT_DIR',realpath(dirname(__FILE__) . '/../models'));
require_once(ROOT_DIR . "/Db.php");



class ReporteController {
    protected $conection;
    
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	public function getReporteInscripcion($dni,$anio,$carrera_id){
        $this->getConection();

        $sql = "SELECT c.descripcion as 'NombreCarrera', p.nombre as 'Nombre', p.apellido as 'Apellido', 
               p.dni as 'DNI', p.fecha_nacimiento as FechaNacimiento, loc.nombre as 'Localidad', prov.nombre as 'Provincia',
               'Argentina' as 'Nacionalidad', p.domicilio as 'Domicilio', 
               p.telefono_numero as 'Telefono_Numero', p.telefono_caracteristica as 'Telefono_Caracteristica',
               p.email as 'Email', ac.fecha_inscripcion as 'FechaInscripcion',
               p.estado_civil as 'EstadoCivil', p.ocupacion as 'Ocupacion',
               p.titulo as 'Titulo', p.titulo_expedido_por as 'Escuela'
         FROM persona p, alumno a, carrera c, alumno_estudia_carrera ac, localidad loc, provincia prov
         WHERE p.dni = ? AND
               p.id = a.idPersona AND 
               a.id = ac.idAlumno AND 
               ac.idCarrera = ? AND 
               ac.idCarrera = c.id AND 
               ac.anio = ? AND
               p.idLocalidad = loc.id AND
               loc.idProvincia = prov.id ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$dni,$carrera_id,$anio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    
    }
}

