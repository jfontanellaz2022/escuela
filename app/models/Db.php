<?php
require_once 'Parameters.php';

class Db {

	private $host;
	private $db;
	private $user;
	private $pass;
	public  $conection;

	public function __construct($host=Parameters::VALOR_DB_HOST,$db_name=Parameters::VALOR_DB_NAME,$db_user=Parameters::VALOR_DB_USER,$db_pass=Parameters::VALOR_DB_PASSWORD) {		
		$this->host = $host;
		$this->db = $db_name;
		$this->user = $db_user;
		$this->pass = $db_pass;

		try {
           $this->conection = new PDO('mysql:host='.$this->host.'; dbname='.$this->db.';charset=utf8mb4', $this->user, $this->pass);
        } catch (PDOException $e) {
            include_once "./error403.html";
            //echo $e->getMessage();
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
