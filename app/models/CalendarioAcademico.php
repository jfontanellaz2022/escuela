<?php 
require_once('Db.php');

class CalendarioAcademico {
	protected $table = 'calendario_academico';
	protected $conection;
	private $anio_lectivo;
	private $periodo_cuatrimestre_id;
	private $fecha_inicio;
	private $fecha_final; 
	private $bedel_id;
	private $evento_id;
	protected $cantidad;

	public function __construct() {
		
	}

	// Set conection 
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	// Get all Alumnos 
	public function getCalendarioEventos(){
		$this->getConection();
		$sql = "SELECT * FROM ".$this->table;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll();
	}

	// Get all Alumnos 
	public function getCalendarioEventosFilter($filtros){
		var_dump('asdsadasdsad');exit;
		$this->getConection();
		$where = "";
		$andX = [];
		$andX[] = " c.idTipificacion = t.id ";
		var_dump('asdsadasdsad');exit;

		if (isset($filtros['id'])) $andX[] = 'c.id = ' . $filtros['id'];
		if (isset($filtros['anio_lectivo'])) $andX[] = 'c.anio_lectivo = ' . $filtros['anio_lectivo'];
		if (isset($filtros['evento'])) $andX[] = " t.nombre like '%" . $filtros['evento'] . "%'";
		if (isset($filtros['fecha_inicio'])) $andX[] = "c.fecha_inicio like '" . $filtros['fecha_inicio'] . "'";
		if (isset($filtros['fecha_final'])) $andX[] = "c.fecha_final like '" . $filtros['fecha_final'] . "'";

		if (count($andX)>0) $where = ' WHERE (' . implode(" and ",$andX) . ') ';
		else $where = '';
		
		$sql = "SELECT c.id, c.anio_lectivo, c.fecha_inicio, c.fecha_final, c.idTipificacion,
		           		t.id as 'tipificacion_id', t.codigo, t.nombre, t.descripcion 
		        FROM calendario_academico c, tipificacion t 
				$where ";

		var_dump($sql);exit;
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $res;

	}

	//Get Calendario by Id 
	public function getCalendarioById($id){
		$this->getConection();
		$sql = "SELECT c.id, c.anio_lectivo, c.fecha_inicio, c.fecha_final, 
		               c.idTipificacion, t.codigo, t.nombre
		        FROM calendario_academico c, tipificacion t 
		        WHERE c.id = ? AND c.idTipificacion = t.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getPeriodoPostInscripcion($inscripcion_id,$turno_id){
		$this->getConection();
		$sql = "SELECT c.*
				FROM calendarioacademico c
				WHERE c.id = $inscripcion_id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$fechaInicio = new DateTime($res['fechaInicioEvento']);

		$sql = "SELECT c.*
				FROM calendarioacademico c
				WHERE c.id = $turno_id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
		$fechaFin = new DateTime($res['fechaFinalEvento']);
		
		// Crear un objeto DateTime para la fecha que quieres verificar
		$fechaVerificarHoy = new DateTime();
		// Verificar si la fecha a verificar está en el rango
		if ($fechaVerificarHoy >= $fechaInicio && $fechaVerificarHoy <= $fechaFin) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	// Get Calendario by Codigo 
	public function getCalendarioByCodigoEvento($codigo){
		$this->getConection();
		$sql = "SELECT c.*, e.descripcion as evento_descripcion 
				FROM calendario_academico c, tipificacion e 
				WHERE e.codigo = ? and e.id=c.idTipificacion
				ORDER BY c.anio_lectivo desc, c.fecha_final desc";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$codigo]);
		$arr_resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $arr_resultado;
	}

	public function getIdCalendarioByCodigo($codigo){
		$this->getConection();
		$sql = "SELECT c.id
				FROM calendarioacademico c, evento e 
				WHERE e.id=c.idEvento and (e.codigo = ? )
				ORDER BY c.fechaFinalEvento desc
				LIMIT 0,1 ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Get Calendario by Codigo 
	public function getInscripcionExamenActiva(){
		$inscripcion_activa = $inscripcion_asociada = $llamados = 0;
		$hoy = date('Y-m-d');
		$arr_resultado = [];
		if (strtotime($hoy)>=strtotime($this->getCalendarioByCodigoEvento(1005)[0]['fechaInicioEvento']) &&
			strtotime($hoy)<=strtotime($this->getCalendarioByCodigoEvento(1005)[0]['fechaFinalEvento'])) {
			$inscripcion_activa = $this->getCalendarioByCodigoEvento(1005)[0]['id'];
			$inscripcion_asociada = $this->getCalendarioByCodigoEvento(1005)[0]['id'];
			$llamados = 2;
		};

		if (strtotime($hoy)>=strtotime($this->getCalendarioByCodigoEvento(1006)[0]['fechaInicioEvento']) &&
			strtotime($hoy)<=strtotime($this->getCalendarioByCodigoEvento(1006)[0]['fechaFinalEvento'])) {
			$inscripcion_activa = $this->getCalendarioByCodigoEvento(1006)[0]['id'];
			$inscripcion_asociada = $this->getCalendarioByCodigoEvento(1006)[0]['id'];
			$llamados = 1;
		};

		if (strtotime($hoy)>=strtotime($this->getCalendarioByCodigoEvento(1007)[0]['fechaInicioEvento']) &&
			strtotime($hoy)<=strtotime($this->getCalendarioByCodigoEvento(1007)[0]['fechaFinalEvento'])) {
			$inscripcion_activa = $this->getCalendarioByCodigoEvento(1007)[0]['id'];
			$inscripcion_asociada = $this->getCalendarioByCodigoEvento(1007)[0]['id'];
			$llamados = 2;
		};

		if (strtotime($hoy)>=strtotime($this->getCalendarioByCodigoEvento(1008)[0]['fechaInicioEvento']) &&
			strtotime($hoy)<=strtotime($this->getCalendarioByCodigoEvento(1008)[0]['fechaFinalEvento'])) {
			$inscripcion_activa = $this->getCalendarioByCodigoEvento(1008)[0]['id'];
			$inscripcion_asociada = $this->getCalendarioByCodigoEvento(1008)[0]['id'];
			$llamados = 1;
		};

		if (strtotime($hoy)>=strtotime($this->getCalendarioByCodigoEvento(1009)[0]['fechaInicioEvento']) &&
			strtotime($hoy)<=strtotime($this->getCalendarioByCodigoEvento(1009)[0]['fechaFinalEvento'])) {
			$inscripcion_activa = $this->getCalendarioByCodigoEvento(1009)[0]['id'];
			$inscripcion_asociada = $this->getCalendarioByCodigoEvento(1005)[0]['id'];
			$llamados = 2;
		};

		if (strtotime($hoy)>=strtotime($this->getCalendarioByCodigoEvento(1010)[0]['fechaInicioEvento']) &&
			strtotime($hoy)<=strtotime($this->getCalendarioByCodigoEvento(1010)[0]['fechaFinalEvento'])) {
			$inscripcion_activa = $this->getCalendarioByCodigoEvento(1010)[0]['id'];
			$inscripcion_asociada = $this->getCalendarioByCodigoEvento(1007)[0]['id'];
			$llamados = 2;
		};
		$arr_resultado['inscripcion_activa'] = $inscripcion_activa;
		$arr_resultado['inscripcion_asociada'] = $inscripcion_asociada;
		//$llamados = 2;
		$arr_resultado['cantidad_llamados'] = $llamados;
		return $arr_resultado;

	} 


	// Devuelve la ultima inscripcion a  examenes
	public function getLastInscripcionExamen(){
		$this->getConection();
		$sql = "SELECT c.*, t.codigo, t.nombre as evento_descripcion 
				FROM calendario_academico c, tipificacion t 
				WHERE t.id=c.idTipificacion and (t.codigo = 1005 or t.codigo = 1006 or t.codigo = 1007 or t.codigo = 1008)
				ORDER BY c.fecha_final desc
				limit 0,1 ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		$arr_resultado = $stmt->fetch(PDO::FETCH_ASSOC);
		return $arr_resultado;
	}


	// Get Calendario by Codigo
	
	public function getInscripcionCursadoActiva(){
		$inscripcion_activa = $inscripcion_asociada = $llamados = 0;
		$hoy = date('Y-m-d');
		$resultado = FALSE;
		$fecha_inicio = (empty($this->getCalendarioByCodigoEvento(1023)) || !$this->getCalendarioByCodigoEvento(1023) )?FALSE:$this->getCalendarioByCodigoEvento(1023)[0]['fechaInicioEvento'];
		$fecha_final = (empty($this->getCalendarioByCodigoEvento(1023)) || !$this->getCalendarioByCodigoEvento(1023) )?FALSE:$this->getCalendarioByCodigoEvento(1023)[0]['fechaFinalEvento'];
		if ($fecha_inicio && $fecha_final) {
    		if (strtotime($hoy)>=strtotime($fecha_inicio) && strtotime($hoy)<=strtotime($fecha_final)) {
    			return TRUE;
    		};
		} else return FALSE;
		return $resultado;
	}

	public function getEventoActivoByCodigo($codigo){
		$hoy = date('Y-m-d');
		$objeto_eventos = $this->getCalendarioByCodigoEvento($codigo);
		$arr_resultado = [];
		foreach ($objeto_eventos as $val) {
			if (strtotime($hoy)>=strtotime($val['fecha_inicio']) &&
				strtotime($hoy)<=strtotime($val['fecha_final'])) {
					$arr_resultado[] = $val;
					break;
			};
		}
		return $arr_resultado;
	}


	public function getEventosArmadoMateriasActivo() {
		$arr_codigos_armado_listas = [];
		for ($ev=1014;$ev<=1016;$ev++) {
			if (!empty($this->getEventoActivoByCodigo($ev))) {
				
				$arr_codigos_armado_listas[] = $ev;
			}
		}
		return $arr_codigos_armado_listas;
	}

	// Save 
	/*public function save($param){
		$this->getConection();

		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			$actualObjeto = $this->getAlumnoById($param["id"]);
			if(isset($actualObjeto["id"])){
				$exists = true;	
				//* Actual values 
				$id = $param["id"];
				$dni = $actualObjeto["dni"];
				$apellido = $actualObjeto["apellido"];
				$nombres = $actualObjeto["nombres"];
				$anio_ingreso = $actualObjeto["anio_ingreso"];
				$debe_titulo = $actualObjeto["debe_titulo"];
				$habilitado = $actualObjeto["habilitado"];
			}
		}

		//* Received values 
		if(isset($param["dni"])) $dni = $param["dni"];
		if(isset($param["apellido"])) $apellido = $param["apellido"];
		if(isset($param["nombres"])) $nombres = $param["nombres"];
		if(isset($param["anio_ingreso"])) $anio_ingreso = $param["anio_ingreso"];
		if(isset($param["debe_titulo"])) $debe_titulo = $param["debe_titulo"];
		if(isset($param["habilitado"])) $habilitado = $param["habilitado"];
		

		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET dni=?, apellido=?, nombre=?, anioIngreso=?, debeTitulo=?, habilitado=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$dni,$apellido,$nombres,$anio_ingreso,$debe_titulo, $habilitado, $id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (dni, apellido, nombre, anioIngreso, debeTitulo, habilitado) values(?, ?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$dni,$apellido,$nombres,$anio_ingreso,$debe_titulo, $habilitado]);
			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}*/

	/* Delete Alumno by id */
	
	public function deleteCalendarioAcademicoById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

}

//$c = new CalendarioAcademico();

//var_dump($c->getEventosArmadoMaterias());



/*
array(10) { ["id"]=> int(123) ["AnioLectivo"]=> int(2023) ["idPeriodoCuatrimestreActivo"]=> NULL ["fechaInicioEvento"]=> string(10) "2023-11-09" ["fechaFinalEvento"]=> string(10) "2023-11-13" ["finalizado"]=> NULL ["idBedel"]=> NULL ["idEvento"]=> int(5) ["descripcion"]=> NULL ["evento_descripcion"]=> string(33) "Inscripcion 3er Turno de Examenes" }
*/

?>
