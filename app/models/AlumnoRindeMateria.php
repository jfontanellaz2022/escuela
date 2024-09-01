<?php 
require_once('Db.php');

class AlumnoRindeMateria {

	protected $table = 'alumno_rinde_materia';
	protected $conection;
	private $id;
	private $alumno_id;
	private $materia_id;
	private $calendario_id; 
	private $llamado;
	private $condicion;
	private $fecha_hora_inscripcion;
	private $fecha_modificacion_nota;
	private $nota;
	private $estado_final;
	private $usuario_id;
	private $inscripcion_tipo;
	protected $cantidad;


	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all Alumnos */
	public function getAlumnosRindenMaterias(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/* Get Alumno by Id */
	public function getAlumnoRindenMateriaById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get Alumno by Dni */
	public function getAlumnoRindeMateriasByIdAlumno($alumno_id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idAlumno = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Get Alumno by Dni */
	public function getAlumnoRindeMateriasByIdMateria($materia_id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idMateria = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$materia_id]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Get Materias by Id Alumno y por Id Calendario 
	Perfil: Profesor
	Proceso: GestionarFechasExamenesFinales
	*/
	public function getMateriasByIdAlumnoByIdCalendario($alumno_id,$calendario_id,$llamado=3){
		$this->getConection();
		if ($llamado==3) {
			$sql = "SELECT * FROM " . $this->table . " WHERE idAlumno = ? and idCalendario = ?";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$alumno_id,$calendario_id]);
		} else {
			$sql = "SELECT * FROM " . $this->table . " WHERE idAlumno = ? and idCalendario = ? and llamado = ?";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$alumno_id,$calendario_id,$llamado]);
		}
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	


	/****************************************************************************************************************
    
    Saca todas las materias que cumplan con un estado especifico. Algunos estados requieren control de vencimiento 
    (La Regularidad y el estado Libre tienen vencimiento).

    estado: 'Aprobo', 'Libre', 'Regularizo', 'Promociono'
    vencimiento: TRUE, FALSE

    *****************************************************************************************************************/

    public function getMateriasRendidasByEstado($alumno_id,$estado)
    {
		$this->getConection();
        $arr_resultado = array();
        $sql = "SELECT idMateria
                  FROM alumno_rinde_materia
                  WHERE idAlumno = ? AND estado_final = ? ";
		
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$alumno_id,$estado]);
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($res as $fila) {
			$arr_resultado[] = $fila['idMateria'];
		}

        return $arr_resultado;
    }


	/****************************************************************************************************************
    
    Saca todas las materias que cumplan con un estado especifico, con su respectivo detalle. 
    Algunos estados requieren control de vencimiento (La Regularidad y el estado Libre tienen vencimiento).

    estado: 'Aprobo', 'Libre', 'Regularizo', 'Promociono'
    vencimiento: TRUE, FALSE

    *****************************************************************************************************************/

    public function getMateriasRendidasByEstadoDetalle($alumno_id,$estado)
    {
		$this->getConection();
        $arr_resultado = [];
        $sql = "SELECT m.id, m.nombre, m.anio, arm.nota, arm.condicion, c.descripcion
                  FROM alumno_rinde_materia arm, materia m, carrera c, carrera_tiene_materia ctm 
                  WHERE arm.idAlumno = ? AND 
                            arm.idMateria = m.id AND 
                            m.id = ctm.idMateria AND
                            ctm.idCarrera = c.id AND 
                          arm.estado_final = ? ";
		$stmt = $this->conection->prepare($sql);
		
		$stmt->execute([$alumno_id,$estado]);
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($res as $fila) {
			$arr_materia = [];
            $arr_materia['idMateria'] = $fila['id'];
            $arr_materia['nombre'] = $fila['nombre'];
            $arr_materia['materia_anio'] = $fila['anio'];
            $arr_materia['nota'] = $fila['nota'];
            $arr_materia['condicion'] = $fila['condicion'];
            $arr_materia['carrera'] = $fila['descripcion'];
            $arr_resultado[] = $arr_materia;
        };      
        return $arr_resultado;
    }


	/* Save Alumno */
	public function save($param){
		$this->getConection();

		// Set default values 
		$id = $idAlumno = $idMateria = $idCalendario = $llamado = $nota = $idUsuario =0 ;
		$condicion = $estado_final = $fecha_hora_inscripcion = $fecha_modificacion_nota = "";

		// Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			$actualAlumnoRindeMateria = $this->getAlumnoRindenMateriaById($param["id"]);
			if(isset($actualAlumnoRindeMateria["id"])){
				$exists = true;	
				// Actual values 
				$id = $param["id"];
				$idAlumno = $actualAlumnoRindeMateria["idAlumno"];
				$idMateria = $actualAlumnoRindeMateria["idMateria"];
				$idCalendario = $actualAlumnoRindeMateria["idCalendario"];
				$llamado = $actualAlumnoRindeMateria["llamado"];
				$nota = $actualAlumnoRindeMateria["nota"];
				$idUsuario = $actualAlumnoRindeMateria["idUsuario"];
				$condicion = $actualAlumnoRindeMateria["condicion"];
				$estado_final = $actualAlumnoRindeMateria["estado_final"];
				$fecha_hora_inscripcion = $actualAlumnoRindeMateria["FechaHoraInscripcion"];
			}
		}


		// Received values 
		if(isset($param["alumno_id"])) $idAlumno = $param["alumno_id"];
		if(isset($param["materia_id"])) $idMateria = $param["materia_id"];
		if(isset($param["calendario_id"])) $idCalendario = $param["calendario_id"];
		if(isset($param["llamado"])) $llamado = $param["llamado"];
		if(isset($param["nota"])) $nota = $param["nota"];
		if(isset($param["condicion"])) $condicion = $param["condicion"];
		if(isset($param["estado_final"])) $estado_final = $param["estado_final"];
		if(isset($param["fecha_hora_inscripcion"])) $fecha_hora_inscripcion = $param["fecha_hora_inscripcion"];
		if(isset($param["usuario_id"])) $idUsuario = $param["usuario_id"];
		

		// Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET idAlumno=?, idMateria=?, idCalendario=?, llamado=?, condicion=?, FechaHoraInscripcion=?, nota=?, estado_final=?, idUsuario=?  WHERE id=?";
			try {
				$stmt = $this->conection->prepare($sql);
				$stmt->execute([$idAlumno,$idMateria,$idCalendario,$llamado,$condicion,$fecha_hora_inscripcion,$nota,$estado_final,$idUsuario,$id]);
			} catch (Exception $e) {
				return -1*$e->getCode();
			}
		} else {
			$sql = "INSERT INTO ".$this->table. " (idAlumno, idMateria, idCalendario, llamado, condicion, FechaHoraInscripcion, nota, estado_final, idUsuario) values(?, ?, ?, ?, ?, ?, ?, ?, ?)";
			try {
				$stmt = $this->conection->prepare($sql);
				$stmt->execute([$idAlumno,$idMateria,$idCalendario,$llamado,$condicion,$fecha_hora_inscripcion,$nota,$estado_final,$idUsuario]);
				$id = $this->conection->lastInsertId();
			} catch (Exception $e) {
				return -1*$e->getCode();
			}
		}

		return $id;	

	} 


	/* Delete Alumno by id */
	public function deleteAlumnoRindeMateriaById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	/* Delete Alumno by id */
	public function deleteAlumnoRindeMateriaByIdAlumnoByIdMateriaByIdCalendario($alumno_id,$materia_id,$calendario_id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE idAlumno = ? and idMateria = ? and idCalendario = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$alumno_id,$materia_id, $calendario_id]);
	}

}

// ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR
/*
$arm = new AlumnoRindeMateria();
$argumentos = ['alumno_id'=>364,'materia_id'=>402,'calendario_id'=>123,'llamado'=>1,'condicion'=>'Regular','fecha_hora_inscripcion'=>'2016-07-11 00:00:00','nota'=>0,'estado_final'=>'Pendiente','fecha_hora_modificacion'=>'2016-07-11 00:00:00','usuario_id'=>3];
var_dump($arm->save($argumentos));
*/
?>
