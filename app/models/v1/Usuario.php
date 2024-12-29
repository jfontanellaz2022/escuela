<?php
require_once('Db.php');

class Usuario {
    protected $table = 'usuario';
	protected $conection;
	private $id;
    private $nombre;
	private $dni;
	private $idTipo;
	private $password; 
	private $password_vencida;
    private $idRol;
    private $idPersona;
    protected $cantidad;

	public function __construct() {
		
	}

    /* Set conection */
	public function getConection(){
		$dbObj = new Db();
		$this->conection = $dbObj->conection;
	}

	/* Get all cantidad */
	public function getCantidad(){
		return $this->cantidad;
    }

	public function getUsuarioById($id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getUsuarioByTipoByDni($tipo,$dni){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idtipo = ? and dni = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$tipo,$dni]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public function getUsuarioByTipoByIDPersona($tipo,$persona_id){
		$this->getConection();
		$sql = "SELECT * FROM " . $this->table . " WHERE idtipo = ? and idPersona = ?";
		$stmt = $this->conection->prepare($sql);
		$stmt->execute([$tipo,$persona_id]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public function setPasswordById($id,$password) {
        $this->getConection();
        $sql = "UPDATE " . $this->table . " SET pass = md5(?) WHERE id = ?";
        $stmt = $this->conection->prepare($sql);
		return $stmt->execute([$password,$id]);
    }


 public function setPasswordByTipoByDni($tipo,$dni,$password) {
        $this->getConection();
        $sql = "UPDATE " . $this->table . " SET pass = md5(?) WHERE idtipo = ? and dni = ?";
        $stmt = $this->conection->prepare($sql);
		return $stmt->execute([$password,$tipo,$dni]);
    }
    
public function verificaPasswordByTipoByDni($tipo,$dni,$password) {
        $this->getConection();
        $sql = "SELECT * FROM " . $this->table . " WHERE idtipo = ? and dni = ? and pass = md5(?)";
        $stmt = $this->conection->prepare($sql);
		$stmt->execute([$tipo,$dni,$password]);
		$arr_res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $arr_res;
    }
    

public function save($param){
    $this->getConection();

    //* Check if exists 
    $exists = false;
    $id = 0;
    if(isset($param["id"]) and $param["id"] !=''){
        $actualObjeto = $this->getUsuarioById($param["id"]);
        if(isset($actualObjeto["id"])){
            $exists = true;	
            //* Actual values 
            $this->id = $param["id"];
            $this->nombre = $actualObjeto["nombre"];
            $this->dni = $actualObjeto["dni"];
            $this->idTipo = $actualObjeto["idtipo"];
            $this->password = $actualObjeto["pass"];
            $this->idRol = $actualObjeto["idRol"];
            $this->idPersona = $actualObjeto["idPersona"];
        }
    }

    //* Received values 
    if(isset($param["nombre"])) $this->nombre = $param["nombre"];
    if(isset($param["dni"])) $this->dni = $param["dni"];
    if(isset($param["idTipo"])) $this->idTipo = $param["idTipo"];
    if(isset($param["password"])) $this->password = $param["password"];
    if(isset($param["idRol"])) $this->idRol = $param["idRol"];
    if(isset($param["idPersona"])) $this->idPersona = $param["idPersona"];

    //* Database operations 
    
    if($exists){
        $sql = "UPDATE ".$this->table. " SET nombre = ?, dni = ? , idtipo = ?, pass = ?, 
                                             idRol = ?, idPersona = ? 
                                         WHERE id = ?";
        try {                                 
            $stmt = $this->conection->prepare($sql);
            $res = $stmt->execute([$this->nombre,$this->dni,$this->idTipo,$this->password, $this->idRol, $this->idPersona, $this->id]);
            $id = $this->id;
        } catch (Exception $e){
            $id = -1;
        }
    } else {
        $sql = "INSERT INTO ".$this->table. " (nombre, dni, idtipo, pass, idRol, idPersona) values(?, ?, ?, ?, ?, ?)";
        $stmt = $this->conection->prepare($sql);
        try {
            $stmt->execute([$this->nombre, $this->dni, $this->idTipo, $this->password, $this->idRol, $this->idPersona]);
            $id = $this->id = $this->conection->lastInsertId();
        } catch (Exception $e){
            $id = -1;
        }

    }

    return $id;	

}    

    




}



//$usuario = new Usuario();
//$res = $usuario->getUsuarioByTipoByDni(1,24912834);


//var_dump($res);


//var_dump($tb->getMateriasCandidatasParaRendir($idAlumno));
//var_dump($tb->getMateriaCorrelativa(409));
//$tb->getArregloMateriasVerificadasParaInscribirse($idAlumno);

//var_dump($tb->getArregloMateriasVerificadasParaInscribirse($idAlumno));
//var_dump($tb->getMateriasPorEstadoDetalle($idAlumno,'Libre',FALSE));


//var_dump($tb->getArregloMateriasVerificadasParaInscribirseDetalles($idAlumno,'Rendir'));

//var_dump($tb->setPasswordPorId(1,'hernan'));
//$tb->setPasswordPorDni('24912834',4,'javier');