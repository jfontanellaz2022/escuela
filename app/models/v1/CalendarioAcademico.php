<?php 
require_once('Db.php');

class CalendarioAcademico {
	protected $table = 'calendario_academico';
	protected $conection;
	private $anio_lectivo;
	private $fecha_inicio;
	private $fecha_final; 
	private $bedel_id;
	private $evento_id;
	protected $cantidad;

	public function getCantidad() {
		return $this->cantidad;
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
		//var_dump('asdsadasdsad');exit;
		$this->getConection();
		$where = "";
		$andX = [];
		$andX[] = " c.idTipificacion = t.id ";

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

	// Get Calendario by Id 
	public function getCalendarioById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Get Calendario by Codigo 
	public function getCalendarioByCodigoEvento($codigo){
		$this->getConection();
		$sql = "SELECT c.*, e.descripcion as evento_descripcion 
				FROM calendarioacademico c, evento e 
				WHERE e.codigo = ? and e.id=c.idEvento
				ORDER BY c.AnioLectivo desc, c.fechaFinalEvento desc";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$codigo]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
		$sql = "SELECT c.*, e.codigo, e.descripcion as evento_descripcion 
				FROM calendarioacademico c, evento e 
				WHERE e.id=c.idEvento and (e.codigo = 1005 or e.codigo = 1006 or e.codigo = 1007 or e.codigo = 1008 or e.codigo = 1009 or e.codigo = 1010)
				ORDER BY c.fechaFinalEvento desc
				limit 0,1 ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
		//var_dump($objeto_eventos);die;
		$arr_resultado = [];
		foreach ($objeto_eventos as $val) {
			if (strtotime($hoy)>=strtotime($val['fechaInicioEvento']) &&
				strtotime($hoy)<=strtotime($val['fechaFinalEvento'])) {
					$arr_resultado[] = $val;
			}
		}
		return $arr_resultado;
	}

	// Save 
	public function save($param){
		$this->getConection();

		$id = $anio_lectivo = $idTipificacion = $fecha_inicio = $fecha_final = "";
		//* Check if exists 
		$exists = false;
		if(isset($param["id"]) and $param["id"] !=''){
			$actualObjeto = $this->getCalendarioById($param["id"]);
			if(isset($actualObjeto["id"])){
				$exists = true;	
				//* Actual values 
				$id = $param["id"];
				$anio_lectivo = $actualObjeto["anio_lectivo"];
				$idTipificacion = $actualObjeto["idTipificacion"];
				$fecha_inicio = $actualObjeto["fecha_inicio"];
				$fecha_final = $actualObjeto["fecha_final"];
			}
		}
		
		//* Received values 
		if(isset($param["anio_lectivo"])) $anio_lectivo = $param["anio_lectivo"];
		if(isset($param["idTipificacion"])) $idTipificacion = $param["idTipificacion"];
		if(isset($param["fecha_inicio"])) $fecha_inicio = $param["fecha_inicio"];
		if(isset($param["fecha_final"])) $fecha_final = $param["fecha_final"];
		
		//* Database operations 
		
		if($exists){
			$sql = "UPDATE ".$this->table. " SET anio_lectivo=?, idTipificacion=?, fecha_inicio=?, fecha_final=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$anio_lectivo, $idTipificacion, $fecha_inicio, $fecha_final, $id]);
		} else {
			$sql = "INSERT INTO ".$this->table. " (anio_lectivo, idTipificacion, fecha_inicio, fecha_final) values(?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			//var_dump([$anio_lectivo, $idTipificacion, $fecha_inicio, $fecha_final]);exit;
			//$stmt->execute([$anio_lectivo, $idTipificacion, "'".$fecha_inicio."'", "'".$fecha_final."'"]);
			$stmt->execute([$anio_lectivo, $idTipificacion, $fecha_inicio, $fecha_final]);
			//$stmt->debugDumpParams();

			$id = $this->conection->lastInsertId();
		}

		return $id;	

	}

	/* Delete Alumno by id */
	
	public function deleteCalendarioAcademicoById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

}

//$c = new CalendarioAcademico();
//var_dump($c->getEventoActivoByCodigo(1021));



/*
array(10) { ["id"]=> int(123) ["AnioLectivo"]=> int(2023) ["idPeriodoCuatrimestreActivo"]=> NULL ["fechaInicioEvento"]=> string(10) "2023-11-09" ["fechaFinalEvento"]=> string(10) "2023-11-13" ["finalizado"]=> NULL ["idBedel"]=> NULL ["idEvento"]=> int(5) ["descripcion"]=> NULL ["evento_descripcion"]=> string(33) "Inscripcion 3er Turno de Examenes" }
*/

?>
