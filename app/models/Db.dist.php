<?php

class Db {

	private $host;
	private $db;
	private $user;
	private $pass;
	public  $conection;

	public function __construct($host='localhost',$db_name='uiakkdaq_escuela',$db_user='uiakkdaq_usuario',$db_pass='1qa_2ws_3ed_4rf') {		
		$this->host = $host;
		$this->db = $db_name;
		$this->user = $db_user;
		$this->pass = $db_pass;

		try {
           $this->conection = new PDO('mysql:host='.$this->host.'; dbname='.$this->db.';charset=UTF8', $this->user, $this->pass);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit();
        }

	}

	function getHost() {
		return $this->host;
	}

	function getUser() {
		return $this->user;
	}

	function getDataBase() {
		return $this->db;
	}

	function getPassword() {
		return $this->pass;
	}

}
