<?php 
define('ROOT_DIR',realpath(dirname(__FILE__) . '/../models'));
require_once(ROOT_DIR . "/Db.php");
require_once(ROOT_DIR . "/Carrera.php");
require_once(ROOT_DIR . "/Profesor.php");
require_once(ROOT_DIR . "/CarreraTieneMateria.php");


class ProfesorController {
   
	/* Get all Carreras */
	public function getCarreras(){
		$objCarrera = new Carrera();
		$arr_res = $objCarrera->getCarreras();
		return $arr_res;
	} 

	/* Get all Carreras de un Profesor */
	public function getAllCarrerasByProfesor($profesor_id){
		$objProfesor = new Profesor();
		$arr_res = $objProfesor->getAllCarrerasByProfesor($profesor_id);
		return $arr_res;
	}

	/* Get all Materias de una Carrera */
	public function getMateriasByIdCarrera($id_carrera){
		$obj = new CarreraTieneMateria();
		$arr_res = $obj->getMateriasByIdCarreraDetalle($id_carrera);
		return $arr_res;
	}





}

//$obj = new ProfesorController();
//var_dump($obj->getMateriasByIdCarreraDetalle(15));