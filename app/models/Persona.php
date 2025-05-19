<?php 
require_once('Db.php');

class Persona {
	protected $table = 'persona';
	protected $conection;

	private $id;
	private $dni;
	private $apellido;
	private $nombres; 
	private $genero;   
	private $fecha_nacimiento;
	private $localidad_id;  
	private $domicilio;
	private $email;
	private $telefono_caracteristica;   
	private $telefono_numero;
	private $estado_civil;
	private $titulo;
	private $titulo_expedido_por;
	private $ocupacion;
	private $observaciones;
	
	protected $cantidad;


	public function __construct() {
		
	}

	/* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	public function getId(){
		return $this->id;
	}

	public function getCantidad(){
		return $this->cantidad;
	}
	
	// Get all Personas 
	public function getPersonas(){
		$this->getConection();
		$sql = "SELECT id as idPersona, dni, apellido, nombre, fecha_nacimiento, nacionalidad, idLocalidad, domicilio, email, 
		        telefono_numero, telefono_caracteristica, observaciones, sexo, estado_civil, ocupacion,
				titulo, titulo_expedido_por
		        FROM persona";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	//Get Persona by Id
	public function getPersonaById($id){
		$this->getConection();
		$sql = "SELECT id as idPersona, dni, apellido, nombre, fecha_nacimiento, nacionalidad, idLocalidad, domicilio, email, 
					   telefono_numero, telefono_caracteristica, observaciones, sexo, estado_civil, ocupacion,
					   titulo, titulo_expedido_por
				FROM persona WHERE id = ? ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	//Get Persona by DNI
	public function getPersonaByDni($dni){
		$this->getConection();
		$sql = "SELECT id as idPersona, dni, apellido, nombre, fecha_nacimiento, nacionalidad, idLocalidad, domicilio, email, 
					   telefono_numero, telefono_caracteristica, observaciones, sexo, estado_civil, ocupacion,
					   titulo, titulo_expedido_por
				FROM persona WHERE dni = ? ";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$dni]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/* Get Persona by Dni */
	public function getPersonaByEmail($email){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE email = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$email]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	//Has Profesor by Id Persona
	public function hasPersona($id){
		$this->getConection();
		$sql = "SELECT p.id as idPersona, p.dni, p.apellido, p.nombre, p.fecha_nacimiento, p.nacionalidad, p.idLocalidad, p.domicilio, p.email, 
					   p.telefono_numero, p.telefono_caracteristica, p.observaciones, p.sexo, p.estado_civil, p.ocupacion,
					   p.titulo, p.titulo_expedido_por
				FROM persona p
				WHERE p.id = ?";
	
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	
	// Save Object Persona
	public function save($param){
		$this->getConection();
		//Check if exists 
		$exists = false;
		if(isset($param["idPersona"]) && $param["idPersona"] !=''){
			$actualobj = $this->getPersonaById($param["idPersona"]);
			if(isset($actualobj["idPersona"])){
				$exists = true;	
				//Actual values 
	     		$this->id = $param["idPersona"];
				$this->dni = $actualobj["dni"];
				$this->apellido = $actualobj["apellido"];
				$this->nombres = $actualobj["nombre"];
				$this->genero = $actualobj["sexo"];
				$this->fecha_nacimiento = $actualobj["fecha_nacimiento"];
				$this->localidad_id = $actualobj["idLocalidad"];
				$this->domicilio = $actualobj["domicilio"];
				$this->email = $actualobj["email"];
				$this->telefono_caracteristica = $actualobj["telefono_caracteristica"];
				$this->telefono_numero = $actualobj["telefono_numero"];
				$this->estado_civil = $actualobj["estado_civil"];
				$this->ocupacion = $actualobj["ocupacion"];
				$this->titulo = $actualobj["titulo"];
				$this->titulo_expedido_por = $actualobj["titulo_expedido_por"];
				$this->observaciones = $actualobj["observaciones"];
			}
		}

		// Received values 
		if(isset($param["dni"])) $this->dni = $param["dni"];
		if(isset($param["apellido"])) $this->apellido = $param["apellido"];
		if(isset($param["nombres"])) $this->nombres = $param["nombres"];
		if(isset($param["genero"])) $this->genero = $param["genero"];
		if(isset($param["fecha_nacimiento"])) $this->fecha_nacimiento = $param["fecha_nacimiento"];
		if(isset($param["localidad_id"])) $this->localidad_id = $param["localidad_id"];
		if(isset($param["domicilio"])) $this->domicilio = $param["domicilio"];
		if(isset($param["email"])) $this->email = $param["email"];
		if(isset($param["telefono_caracteristica"])) $this->telefono_caracteristica = $param["telefono_caracteristica"];
		if(isset($param["telefono_numero"])) $this->telefono_numero = $param["telefono_numero"];
		if(isset($param["estado_civil"])) $this->estado_civil = $param["estado_civil"];
		if(isset($param["ocupacion"])) $this->ocupacion = $param["ocupacion"];
		if(isset($param["titulo"])) $this->titulo = $param["titulo"];
		if(isset($param["titulo_expedido_por"])) $this->titulo_expedido_por = $param["titulo_expedido_por"];
		if(isset($param["observaciones"])) $this->observaciones = $param["observaciones"];
		//Database operations 
		if($exists){
			$p = [$this->dni, $this->apellido, $this->nombres, $this->fecha_nacimiento, $this->localidad_id, 
			      $this->domicilio, $this->email, $this->telefono_caracteristica, $this->telefono_numero, $this->genero, 
				  $this->estado_civil, $this->ocupacion, $this->titulo, $this->titulo_expedido_por, $this->observaciones, $this->id];
			//var_dump($p);exit;
			$sql = "UPDATE persona SET dni=?, apellido=?, nombre=?, fecha_nacimiento=?, idLocalidad=?, 
			                           domicilio=?, email=?, telefono_caracteristica=?, telefono_numero=?, sexo=?, 
									   estado_civil=?, ocupacion=?, titulo=?, titulo_expedido_por=?, observaciones=? WHERE id=?";
			$stmt = $this->conection->prepare($sql);
			$res = $stmt->execute($p);
			//$stmt->debugDumpParams();exit;

		} else {
			$p = [$this->dni, $this->apellido, $this->nombres, $this->fecha_nacimiento, $this->localidad_id, 
			      $this->domicilio, $this->email, $this->telefono_caracteristica, $this->telefono_numero, $this->genero, 
				  $this->estado_civil, $this->ocupacion, $this->titulo, $this->titulo_expedido_por, $this->observaciones];
			$sql = "INSERT INTO persona (dni, apellido, nombre, fecha_nacimiento, idLocalidad, domicilio, email, telefono_caracteristica, telefono_numero, sexo, estado_civil, ocupacion, titulo, titulo_expedido_por, observaciones) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?)";
			$stmt = $this->conection->prepare($sql);
			$stmt->execute($p);
			$this->id = $this->conection->lastInsertId();
		}
		return $this->id;	

	}

	// Delete by Id 
	public function deletePersonaById($id){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$id]);
	}

	// Delete Persona by Dni
	public function deletePersonaByDni($dni){
		$this->getConection();
		$sql = "DELETE FROM ".$this->table. " WHERE dni = ?";
		$stmt = $this->conection->prepare($sql);
		return $stmt->execute([$dni]);
	}

}


/*$p = new Persona();
$p->save(["dni"=>"24112730", "apellido"=>"Fonta", "nombres"=>"Javier Hernan", "fecha_nacimiento"=>"2020-02-01", "localidad_id"=>"1401", 
          "domicilio"=>"Espora 2278", "email"=>"jfontane@frsf.com.ar", "telefono_caracteristica"=>"342", "telefono_numero"=>"4604140", "genero"=>"M", 
		  "estado_civil"=>"Soltero", "ocupacion"=>"Estudiante", "titulo"=>"Bachiller en informatica", "titulo_expedido_por"=>"Escuela de comercio", "observaciones"=>"Ninguna observacion","idPersona"=>640]);
*/
?>
