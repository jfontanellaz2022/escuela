<?php 
define('ROOT_DIR',realpath(dirname(__FILE__) . '/../models'));

require_once ROOT_DIR . "/Profesor.php";
require_once ROOT_DIR . "/Carrera.php";
require_once ROOT_DIR . "/Materia.php";
require_once ROOT_DIR . "/Alumno.php";
require_once ROOT_DIR . "/CarreraTieneMateria.php";
require_once ROOT_DIR . "/MateriaFechaExamen.php";
require_once ROOT_DIR . "/ProfesorPerteneceCarrera.php";
require_once ROOT_DIR . "/ProfesorDictaMateria.php";


class ProfesorController {
   
	/* Get all Carreras */
	public function getCarreras(){
		$objCarrera = new Carrera();
		$arr_res = $objCarrera->getCarreras();
		return $arr_res;
	} 

	/* Get all Materias */
	public function getMaterias(){
		$obj = new Materia();
		$arr_res = $obj->getMaterias();
		return $arr_res;
	} 

	/* Get all Carreras de un Profesor */
	public function getAllCarrerasByProfesor($profesor_id){
		$objProfesor = new Profesor();
		$arr_res = $objProfesor->getAllCarrerasByProfesor($profesor_id);
		return $arr_res;
	}

	/* Get all Materias de una Carrera */
	public function getMateriasByCarrera($id_carrera){
		$obj = new CarreraTieneMateria();
		$arr_res = $obj->getMateriasByIdCarreraDetalle($id_carrera);
		return $arr_res;
	}


	/* Get all Materias de una Carrera */
	/* getMateriasByCarreraByProfesor(["profesor_id"=>3,"carrera_id"=>15, "turno_id"=>128])*/
	public function getMateriasByCarreraByProfesor($param){
		$arr_materias = [];
		$carrera = $profesor = $turno = NULL;
		if (isset($param['profesor_id']) && !empty($param['profesor_id']) and
			isset($param['carrera_id']) && !empty($param['carrera_id'])) {

			$carrera = new Carrera;
			$arr_materias_carrera = $carrera->getMateriasPorIdCarrera($param['carrera_id']);
			
			$profesor = new Profesor();
			$arr_materias_dicta = $profesor->getMateriasByProfesor($param['profesor_id']);

			if (isset($param['turno_id']) && !empty($param['turno_id'])) {
				$turno = new MateriaFechaExamen();
			}
			
			foreach ($arr_materias_dicta as $materia_item) {
				foreach ($arr_materias_carrera as $mat_carrera_item) {
					if ($materia_item['materia_id']==$mat_carrera_item['materia_id']) {
						$materia_item_tmp = $materia_item;
						if (isset($param['turno_id']) && !empty($param['turno_id'])) {
							$fecha_examen = $turno->getMateriaFechaExamenByIdMateriaByIdCalendario($materia_item['materia_id'],$param['turno_id']);
							$materia_item_tmp['fecha_examen'] = $fecha_examen;
						}
						$arr_materias[] = $materia_item_tmp;
					};   
				}
			}
		}

		
		return $arr_materias;
	}


	/* getMateria(["materia_id"=>410] */
	public function getMateria($param) {
		$materia = NULL;
		$arr_datos_materia = [];
		if ( isset($param['materia_id']) && !empty($param['materia_id']) ) {
			$materia = new Materia();
			$arr_datos_materia = $materia->getMateriaById($param['materia_id']);
		}
		return $arr_datos_materia;
	}


	/* getMateria(["materia_id"=>410,"libres"=>TRUE, "anio"=>2025] */
	public function getAllAlumnosByMateria($param) {
		$materia = NULL;
		$arr_datos_materia = [];
		//if ( isset($param['materia_id']) {
			$materia = new Alumno();
			$arr_datos_materia = $materia->getAllAlumnosByMateriaDetalle($param);
		//};

		return $arr_datos_materia;
	}

	/* getMateria(["materia_id"=>410,"libres"=>TRUE, "anio"=>2025] */
	public function setProfesorMateria($param) {
		$res = FALSE;
		if ( isset($param['profesor_id']) && $param['profesor_id']!=NULL && 
			 isset($param['materia_id']) && $param['materia_id']!=NULL ) {
			$obj = new ProfesorDictaMateria();
			$res = $obj->save(['profesor_id'=>$param['profesor_id'],"materia_id"=>$param['materia_id']]);
		};
		return $res;
	}


		/* getMateria(["materia_id"=>410,"libres"=>TRUE, "anio"=>2025] */
		public function setProfesorCarrera($param) {
			$res = FALSE;
			if ( isset($param['profesor_id']) && $param['profesor_id']!=NULL && 
				 isset($param['carrera_id']) && $param['carrera_id']!=NULL ) {
				$obj = new ProfesorPerteneceCarrera();
				$res = $obj->save(['profesor_id'=>$param['profesor_id'],"carrera_id"=>$param['carrera_id']]);
			}
			return $res;
		}

	public function deleteProfesorMateria($param) {
		$res = FALSE;
		if ( isset($param['profesor_id']) && $param['profesor_id']!=NULL && 
	     	 isset($param['materia_id']) && $param['materia_id']!=NULL ) {
			$obj = new ProfesorDictaMateria();
			$res = $obj->deleteByProfesorByMateria($param['profesor_id'],$param['materia_id']);
		}
		return $res;

	}


	public function deleteProfesorCarrera($param) {
		$res = FALSE;

		if ( isset($param['profesor_id']) && $param['profesor_id']!=NULL && 
	     	 isset($param['carrera_id']) && $param['carrera_id']!=NULL ) {

			$objetoProfesor = new Profesor();
			$objetoPPC = new ProfesorPerteneceCarrera();
		  
			$arr_materias_en_la_carrera = $objetoProfesor->getAllMateriasByProfesorByCarrera($param['profesor_id'],$param['carrera_id']);
		  
			if (is_array($arr_materias_en_la_carrera) && count($arr_materias_en_la_carrera)>0) {
				  $objPDM = new ProfesorDictaMateria();
				  foreach ($arr_materias_en_la_carrera as $item) {
							$objPDM->deleteByProfesorByMateria($param['profesor_id'],$item['materia_id']);
				  };
				  $res = TRUE;
		  
			} 
			
			$objetoPPC->deleteProfesorPerteneceCarreraByProfesorByCarrera($param['profesor_id'],$param['carrera_id']);
			$res = TRUE;

		}

		return $res;

	}




	/* getMateria(["materia_id"=>410,"libres"=>TRUE, "anio"=>2025] */
	/*public function getAllMateriasByCarreraByProfesor($carrera_id,$profesor_id,$turno_id = NULL) {
		$arr_materias = [];
		if ($profesor_id && $carrera_id) {
   			$carrera = new Carrera;
			$arr_materias_carrera = $carrera->getMateriasPorIdCarrera($carrera_id);
			$profesor = new Profesor();
			$arr_materias_dicta = $profesor->getMateriasByProfesor($profesor_id);
		 
			if ($turno_id!=NULL) { // ES PARA REUTILIZAR TANTO EN EXAMENES CON EN CURSADO
			   $materia_tiene_fecha = new MateriaFechaExamen();
			}
		 
			 
			foreach ($arr_materias_dicta as $materia_item) {
			   foreach ($arr_materias_carrera as $mat_carrera_item) {
				  if ($materia_item['materia_id']==$mat_carrera_item['materia_id']) {
					 $materia_item_tmp =$materia_item;
					 if ($turno_id!=NULL) {
						$fecha_examen = $materia_tiene_fecha->getMateriaFechaExamenByIdMateriaByIdCalendario($materia_item['materia_id'],$turno_id);
						$materia_item_tmp['fecha_examen'] = $fecha_examen;
					 }
					 $arr_materias[] = $materia_item_tmp;
				  };   
			   }
			}
			
		 }

		 return $arr_materias;
		 
	}*/








}

//$obj = new ProfesorController();
//var_dump($obj->getAllMateriasByCarreraByProfesor(15,3));