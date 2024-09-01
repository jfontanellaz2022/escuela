<?php
require_once "Persona.php";

class Usuario extends Persona
{
    protected $table = 'usuario';
	protected $conection;
	
    /*private $id;
    private $usuario; 
	private $password; 
    private $habilitado;
    private $idPersona; 

    protected $cantidad;*/

	public function __construct() {
		
	}

    /* Set conection */
	


    public function autenticar($name,$password){
		$this->getConection();
        $password_encriptada = md5($password);
        //var_dump("ppp:" . $name . "ppp:" . $password_encriptada);exit;
		$sql = "SELECT u.id, u.nombre as usuario_nombre, u.pass, u.idPersona, p.dni, p.apellido, p.nombre, p.fechaNacimiento,
                       p.idLocalidad, p.domicilio, p.email, p.telefono_caracteristica, p.telefono_numero
                FROM " . $this->table . " u, persona p 
                WHERE u.nombre = ? AND u.pass = ? AND u.idPersona = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$name,$password_encriptada]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public function getUsuarioById($id){
		$this->getConection();
		$sql = "SELECT u.id as idUsuario,u.nombre usuario_nombre, u.password, u.habilitado, u.idPersona, p.* FROM " . $this->table . " u, persona p 
                WHERE u.id = ? AND u.idPersona = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public function getUsuarioByName($name){
		$this->getConection();
		$sql = "SELECT u.id as idUsuario,u.nombre usuario_nombre, u.password, u.habilitado, u.idPersona, p.* FROM " . $this->table . " u, persona p 
                WHERE u.nombre = ? AND u.idPersona = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$name]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    //* SACA TODAS LAS CREDENCIALES DE UN USUARIO, ES DECIR A QUE SUBSISTEMAS PUEDE INGRESAR EN FUNCION DE SU ROL 
    public function getCredencialesByIdPersona($persona_id){
        $arr_credenciales = [];
        if (!empty($this->hasProfesor($persona_id))) {
            $arr_credenciales[] = 'Profesor';;
        }
        if (!empty($this->hasAlumno($persona_id))) {
            $arr_credenciales[] = 'Alumno';;
        }
        if (!empty($this->hasBedel($persona_id))) {
            $arr_credenciales[] = 'Bedel';
        }
        
		return $arr_credenciales;
	}


    public function setPasswordById($id,$password) {
        $sql = "UPDATE " . $this->table . " SET password = ? WHERE id = ?";
        $stmt = $this->conection->prepare($sql);
		return $stmt->execute([md5($password),$id]);
    }


/* Save Usuario */
public function save($param){
    $this->getConection();

    //* Check if exists 
    $exists = false;
    if(isset($param["id"]) and $param["id"] !=''){
        $actualObjeto = $this->getUsuarioById($param["id"]);
        if(isset($actualObjeto["id"])){
            $exists = true;	
            //* Actual values 
            $this->id = $param["id"];
            $this->nombre = $actualObjeto["usuario_nombre"];
            $this->idTipo = $actualObjeto["idtipo"];
            $this->password = $actualObjeto["password"];
            $this->idPersona = $actualObjeto["idPersona"];
        }
    }

    //* Received values 
    if(isset($param["id"])) $this->dni = $param["id"];
    if(isset($param["nombre"])) $this->idTipo = $param["nombre"];
    if(isset($param["password"])) $this->password = $param["password"];
    if(isset($param["habilitado"])) $this->password = $param["habilitado"];
    if(isset($param["idPersona"])) $this->password_vencida = $param["idPersona"];

    //* Database operations 
    
    if($exists){
        $sql = "UPDATE ".$this->table. " SET nombre = ? , password = ?, habilitado = ?, idPersona = ? WHERE id = ?";
        $stmt = $this->conection->prepare($sql);
        $res = $stmt->execute([$this->nombre,md5($this->password),$this->habilitado, $this->idPesona,$this->id]);
    } else {
        $sql = "INSERT INTO ".$this->table. " (nombre, password, habilitado, idPersona) values(?, ?, ?, ?)";
        $stmt = $this->conection->prepare($sql);
        $stmt->execute([$this->nombre,md5($this->password),$this->habilitado, $this->idPersona]);
        $this->id = $this->conection->lastInsertId();
    }

    return $this->id;

}    

    




}



//$usuario = new Usuario();
//var_dump($usuario->getCredencialesByIdPersona(99));
//$res = $usuario->getUsuarioById(1);
//$res = $usuario->autenticar('jfontanellaz','jhframbo');
//var_dump($res);

