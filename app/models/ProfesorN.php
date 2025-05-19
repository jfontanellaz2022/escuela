<?php 
require_once('Persona.php');

class ProfesorN extends Persona{
	private $idProfesor;
	public function __construct() {
		
	}

	//Get all Profesores 
	public function getProfesores(){
		$arr_resultado = $arr_profesores = $arr_datos_persona = [];
		$this->getConection();
		$sql = "SELECT id as idProfesor, idPersona FROM profesor";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		$arr_profesores = $stmt->fetchAll(PDO::FETCH_ASSOC); 
		foreach ($arr_profesores as $profesor) {
			$persona_id = $profesor['idPersona'];
			$arr_datos_persona = $this->getPersonaById($persona_id);
			$arr_resultado[] = array_merge($arr_profesores, $arr_datos_persona);
		}
		return $arr_resultado;
	}

	
	//Get Profesor by Id 
	public function getProfesorById($id){
		$arr_resultado = $arr_profesor = $arr_datos_persona = [];
		$this->getConection();
		$sql = "SELECT id as idProfesor, idPersona FROM profesor WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		$arr_profesor = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($arr_profesor)) {
			$persona_id = $arr_profesor['idPersona'];
			$arr_datos_persona = $this->getPersonaById($persona_id);
			$arr_resultado = array_merge($arr_profesor, $arr_datos_persona);
		}
		return $arr_resultado;
	}

	//Get Profesor by DNI 
	public function getProfesorByDni($dni){
		$arr_persona = $this->getPersonaByDni($dni);
		$arr_resultado = $arr_profesor = [];
		if (!empty($arr_persona)) {
			$persona_id = $arr_persona['idPersona'];
			$this->getConection();
			$sql = "SELECT id as idProfesor FROM profesor WHERE idPersona = ?";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$persona_id]);
			$arr_profesor = $stmt->fetch(PDO::FETCH_ASSOC);
			$arr_resultado = array_merge($arr_profesor,$arr_persona);
		}
		return $arr_resultado;
	}
	//[['idProfesor'=>'x','idPersona'=>'x', 'dni'=>'x', 'apellido'=>'x', 'nombre'=>'x', 'fechaNacimiento'=>'x', 'nacionalidad'=>'x', 'idLocalidad'=>'x',
	// 'domicilio'=>'x', 'email'=>'x','telefono_numero'=>'x', 'telefono_caracteristica'=>'x', 'observaciones'=>'x', 'sexo'=>'x', 'estado_civil'=>'x',
	// 'ocupacion'=>'x','titulo'=>'x', 'titulo_expedido_por'=>'x']]
	
	// Get by Id Persona 
	public function getProfesorByIdPersona($persona_id){
		$arr_persona = $this->getPersonaById($persona_id);
		$arr_resultado = $arr_profesor = [];
		if (!empty($arr_persona)) {
			$this->getConection();
			$sql = "SELECT id as idProfesor FROM profesor WHERE idPersona = ?";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$persona_id]);
			$arr_profesor = $stmt->fetch(PDO::FETCH_ASSOC);
			$arr_resultado = array_merge($arr_profesor,$arr_persona);
		}
		return $arr_resultado;
	}
    //['idProfesor'=>'x','idPersona'=>'x', 'dni'=>'x', 'apellido'=>'x', 'nombre'=>'x', 'fechaNacimiento'=>'x', 'nacionalidad'=>'x', 'idLocalidad'=>'x',
	// 'domicilio'=>'x', 'email'=>'x','telefono_numero'=>'x', 'telefono_caracteristica'=>'x', 'observaciones'=>'x', 'sexo'=>'x', 'estado_civil'=>'x',
	// 'ocupacion'=>'x','titulo'=>'x', 'titulo_expedido_por'=>'x']
	
	
	// Save Profesor 
	public function save($param){
		
		//DEBERIA CREAR UN USUARIO EN EL CASO QUE SEA UN PROFESOR NUEVO

		// 1 - SI NO HAY ID PERSONA Y HAY OTROS CAMPOS DE PERSONA ENTONCES CREA UNA NUEVA PERSONA SI HAY DATOS OBLIGATORIOS DE PERSONA Y ASIGNAR EL ID DE PERSONA AL PROFESOR
		// 2 - SI HAY ID DE PERSONA Y OTROS CAMPOS DE PERSONA ENTONCES ACTUALIZA LOS DATOS QUE VIENEN PARA EDITAR
		// 3 - SI NO HAY CAMPOS DE PERSONA ENTONCES VERIFICAR QUE LA PERSONA EXISTE Y ASIGNAR EL ID DE PERSONA AL PROFESOR

		$persona_id = $bandera_modifica_datos_pesona = FALSE;
		$param_profesor = $param_persona = [];

		if (isset($param['idProfesor'])) {
				$param_profesor['idProfesor'] = $param['idProfesor'];
		};
		if (isset($param['apellido'])) {
			$param_persona['apellido'] = $param['apellido'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['nombres'])) {
			$param_persona['nombres'] = $param['nombres'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['dni'])) {
			$param_persona['dni'] = $param['dni'];
			$bandera_modifica_datos_pesona = TRUE;
			$arr_datos_persona = $this->getPersonaByDni($param['dni']);
			if (!empty($arr_datos_persona)) {
				$param_persona['idPersona'] = $persona_id = $arr_datos_persona["idPersona"];
			}
		};
		if (isset($param['fecha_nacimiento'])) {
			$param_persona['fecha_nacimiento'] = $param['fecha_nacimiento'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['nacionalidad'])) {
			$param_persona['nacionalidad'] = $param['nacionalidad'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['localidad_id'])) {
			$param_persona['localidad_id'] = $param['localidad_id'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['domicilio'])) {
			$param_persona['domicilio'] = $param['domicilio'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['email'])) {
			$param_persona['email'] = $param['email'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['telefono_caracteristica'])) {
			$param_persona['telefono_caracteristica'] = $param['telefono_caracteristica'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['telefono_numero'])) {
			$param_persona['telefono_numero'] = $param['telefono_numero'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['observaciones'])) {
			$param_persona['observaciones'] = $param['observaciones'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['sexo'])) {
			$param_persona['sexo'] = $param['sexo'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['estado_civil'])) {
			$param_persona['estado_civil'] = $param['estado_civil'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['ocupacion'])) {
			$param_persona['ocupacion'] = $param['ocupacion'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['titulo'])) {
			$param_persona['titulo'] = $param['titulo'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		if (isset($param['titulo_expedido_por'])) {
			$param_persona['titulo_expedido_por'] = $param['titulo_expedido_por'];
			$bandera_modifica_datos_pesona = TRUE;
		};
		// *** DEBERIA IR TB EL USUARIO QUE MODIFICA EL REGISTRO ***

		//$param_persona[$index] = $valor;

		if ($bandera_modifica_datos_pesona) {
			//die("*:" . $persona_id);
			//die("aca 1");
			//var_dump($param_persona);exit;
			$persona_id = parent::save($param_persona);
			//die("aca 2");
		}

		$this->getConection();

		// Check if exists 
		$exists = false;
		if(isset($param_profesor["idProfesor"]) && $param_profesor["idProfesor"] !=''){
			$actualObjeto = $this->getProfesorById($param["idProfesor"]);
			if(isset($actualObjeto["idProfesor"])){
				$exists = true;	
				//Actual values 
				$this->idProfesor = $param["idProfesor"];
			}
		}

		// Received values 

		// Database operations 
		
		if($exists && $persona_id){
			$sql = "UPDATE profesor SET idPersona = ? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute([$persona_id,$this->idProfesor]);
		} if(!$exists && $persona_id) { 
			//Tenemos la Persona creada y ahora se crea el Profesor || Luego se deberia CREAR  el Usuario si no esta creado
			$sql = "INSERT INTO profesor (idPersona) values(?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute([$persona_id]);
			$this->idProfesor = $this->conection->lastInsertId();
		} else {
			return FALSE;
		}
		return $this->idProfesor;	
	}


	/*
	// Delete Alumno by id 
	public function deleteProfesorById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}


	// Get all Carreras por id profesor 
	public function getAllCarrerasByProfesor($profesor_id){
		$this->getConection();
		$sql = "SELECT c.id, c.descripcion, c.habilitada, c.imagen, ppc.idProfesor 
		        FROM profesor p, profesor_pertenece_carrera ppc, carrera c 
		        WHERE p.id = ? and p.id = ppc.idProfesor and ppc.idCarrera = c.id ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Get all Carreras por id profesor 
	public function getAllMateriasByProfesor($profesor_id){
		$this->getConection();
		$sql = "SELECT p.id, c.descripcion, c.habilitada, c.imagen, ppc.idProfesor  
		        FROM materiamateria m, profesor_pertenece_carrera ppc, carrera c 
		        WHERE p.id = ? and p.id = ppc.idProfesor and ppc.idCarrera = c.id ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Get all Materias por id profesor e id carrera
	public function getAllMateriasByProfesorByCarrera($profesor_id,$carrera_id){
		require_once('Carrera.php');
		require_once('ProfesorDictaMateria.php');
		$arr_materias_dicta_profesor_en_la_carrera = [];
		$objCarrera = new Carrera();
		$arr_materias_de_la_carrera = $objCarrera->getMateriasPorIdCarrera($carrera_id);

		$objProfesorDictaMateria = new ProfesorDictaMateria();
		$arr_todas_materias_dicta_profesor = $objProfesorDictaMateria->getByIdProfesor($profesor_id);
	
		foreach($arr_todas_materias_dicta_profesor as $item_materias_dicta_profesor) {
			foreach ($arr_materias_de_la_carrera as $item_materia_carrera) {
				if ($item_materias_dicta_profesor['idMateria']==$item_materia_carrera['materia_id']) {
					$arr_materias_dicta_profesor_en_la_carrera[] = $item_materia_carrera;
				}
			}
		}

		return $arr_materias_dicta_profesor_en_la_carrera;
	}*/

	//Desvincula todas Carreras por id profesor 
	public function desvincularCarreraByProfesor($profesor_id,$carrera_id){
		$this->getConection();
		$sql = "DELETE profesor_pertenece_carrera where idProfesor = ? and idCarrera = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id,$carrera_id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	} 

	//Desvincula todas Carreras por id profesor 
	public function desvincularAllCarrerasByProfesor($profesor_id){
		$this->getConection();
		$sql = "DELETE profesor_pertenece_carrera where idProfesor = ? ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$profesor_id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	} 
	
}

$p = new ProfesorN();

//Ejemplo 1 Alta Profesor con Alta de Persona
//$p->save(["apellido"=>"Bernaneu","nombres"=>"Germansio","dni"=>24219438, "domicilio"=>"Real Sociedad 1145","email"=>"gbernabeu@gmail.com","telefono_caracteristica"=>"0342",
//          "telefono_numero"=>"45588574","localidad_id"=>"1407"]);

//Ejemplo 2 Alta Profesor con Actualizacion de datos de Persona
//$p->save(["apellido"=>"Bernaneuuu","nombres"=>"Germansiooo","dni"=>24219438, "domicilio"=>"Real Sociedad 1145","email"=>"gbernabeu@gmail.com","telefono_caracteristica"=>"0342",
//          "telefono_numero"=>"45588574","localidad_id"=>"1407"]);

//Ejemplo 3 Modificar Profesor con Actualizacion de datos de Persona

//$p->save(["idProfesor"=>156,"apellido"=>"Bernaneuuu","nombres"=>"Germansiooo","dni"=>24219438, "domicilio"=>"Real Sociedad 1145","email"=>"gbernabeu@gmail.com",
//            "telefono_caracteristica"=>"0342","telefono_numero"=>"45588574","localidad_id"=>"1407"]);		  




?>
