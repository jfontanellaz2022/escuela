<?php
require_once "Persona.php";

class Usuario extends Persona
{
    protected $table = 'usuario';
	protected $conection;
    private $id;
	
	public function __construct() {
		
	}

    /* Set conection */
	

    // Autentica y si es verdadero pone todos los datos en un arreglo de sesion
    public function autenticar($name,$password){
		$this->getConection();
        $password_encriptada = md5($password);
		$sql = "SELECT u.id, u.nombre as usuario_nombre, u.password, u.idPersona, u.idRol, u.password_vencida,
                       r.descripcion as rol_descripcion,
                       p.dni, p.apellido, p.nombre, p.fecha_nacimiento,
                       p.idLocalidad, p.domicilio, p.email, 
                       p.telefono_caracteristica, p.telefono_numero
                FROM usuario u, persona p, rol r
                WHERE u.nombre = ? AND u.password = ? AND u.idPersona = p.id and u.idRol=r.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$name,$password_encriptada]);
        //$stmt->debugDumpParams(); exit;
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public function getUsuarioById($id){
		$this->getConection();
		$sql = "SELECT u.id as idUsuario, u.nombre as usuario_nombre, u.password,  u.idRol, u.idPersona, p.* FROM usuario u, persona p 
                WHERE u.id = ? AND u.idPersona = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public function getUsuarioByIdPersona($idPersona){
		$this->getConection();
		$sql = "SELECT u.id as idUsuario, u.nombre as usuario_nombre, u.password, u.idRol, u.idPersona, p.* FROM usuario u, persona p 
                WHERE u.idPersona = ? AND u.idPersona = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$idPersona]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}


   
    public function getUsuarioByName($name){
		$this->getConection();
		$sql = "SELECT u.id as idUsuario, u.nombre as usuario_nombre, u.password,  u.idRol, u.idPersona, p.* FROM usuario u, persona p 
                WHERE u.nombre = ? AND u.idPersona = p.id";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$name]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}


    //* SACA TODAS LAS CREDENCIALES DE UN USUARIO, ES DECIR A QUE SUBSISTEMAS PUEDE INGRESAR EN FUNCION DE SU ROL 
    public function getCredencialesByIdPersona($rol_id){
        $arr_credenciales = [];
        if ($rol_id==3) {
            $arr_credenciales[] = 'Profesor';
        } else if ($rol_id==4) {
            $arr_credenciales[] = 'Alumno';
        } else if ($rol_id==5) {
            $arr_credenciales[] = 'Bedel';
        } else if ($rol_id==6) {
            $arr_credenciales[] = 'Bedel';
            $arr_credenciales[] = 'Profesor';
        } else if ($rol_id==7) {
            $arr_credenciales[] = 'Profesor';
            $arr_credenciales[] = 'Alumno';
        }
        
		return $arr_credenciales;
	}


    public function setPasswordById($id,$password) {
        $this->getConection();
        $password_encriptada = md5($password);
        $sql = "UPDATE usuario SET `password` = ?, password_vencida = 'No' WHERE id = ?";
        $stmt = $this->conection->prepare($sql);
        $stmt = $this->conection->prepare($sql);
        $res = $stmt->execute([$password_encriptada,$id]);
		return $res;
    }


/* Save Usuario */
public function save($param){
    $this->getConection();

    $id = $nombre = $password = $idPersona = $idRol = 0;
    //* Check if exists 
    $exists = false;
    if(isset($param["id"]) and $param["id"] !=''){
        $actualObjeto = $this->getUsuarioById($param["id"]);
        if(isset($actualObjeto["id"])){
            $exists = true;	
            //* Actual values 
            $id = $param["id"];
            $nombre = $actualObjeto["usuario_nombre"];
            $password = $actualObjeto["password"];
            $idPersona = $actualObjeto["idPersona"];
        }
    }

    //* Received values 
    if(isset($param["id"])) $id = $param["id"];
    if(isset($param["nombre"])) $nombre = $param["nombre"];
    if(isset($param["password"])) $password = $param["password"];
    if(isset($param["idPersona"])) $idPersona = $param["idPersona"];
    if(isset($param["idRol"])) $idRol = $param["idRol"];


    //* Database operations 
    
    if($exists){
        $sql = "UPDATE usuario SET nombre = ? , password = ?, idPersona = ?, idRol = ? WHERE id = ?";
        $stmt = $this->conection->prepare($sql);
        $res = $stmt->execute([$nombre, md5($password), $idPersona, $idRol, $id]);
        //$stmt->debugDumpParams();exit;
    } else {
        $sql = "INSERT INTO usuario (nombre, password, idPersona, idRol) values(?, ?, ?, ?)";
        $stmt = $this->conection->prepare($sql);
        $stmt->execute([$nombre, md5($password), $idPersona, $idRol]);
        //$stmt->debugDumpParams();exit;
        $this->id = $this->conection->lastInsertId();
    }

    return $this->id;

}    


/* Save Usuario */
public function setNombre($param){
    $this->getConection();
    $nombre = $id = 0;
    $res = false;
    if(isset($param["id"]) && $param["id"] !='' && 
       isset($param["nombre"]) && $param["nombre"] != '') {
        
            $nombre = $param["nombre"];
            $id = $param["id"];
            $sql = "UPDATE usuario SET nombre = ? WHERE id = ?";
            try {
                $stmt = $this->conection->prepare($sql);
                $stmt->execute([$nombre, $id]);
                $res = 1;
            } catch (Exception $e) {
                $res = $e->getCode();
            }
            
            return $res;

        }
   
    return false;

}  

    




}



//$usuario = new Usuario();
//var_dump($usuario->setNombre(["idPersona"=>99, 'nombre'=>'39661121']));
//var_dump($usuario->getCredencialesByIdPersona(99));
//$res = $usuario->getUsuarioById(1);
//var_dump($usuario->setPasswordById(1889, '1q2w3e4R_'));
//$res = $usuario->autenticar('jfontanellaz','jhframbo');
//var_dump($res);

