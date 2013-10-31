<?php
class conexion{
	private static $db;
	private static $config = array();


	function __construct($db){
		$this->db = $db;

		$this->config = array(
			'host' => 'localhost', 
			'db'   => $this->db, 
			'usr'  => 'root', 
			'pass' => 'root',
		);
	} 

	private function getHost(){
		return $this->config['host'];
	}
	private function getDB(){
		return $this->config['db'];		
	}
	private function getUsr(){
		return $this->config['usr'];
	}
	private function getPass(){
		return $this->config['pass'];
	}
	public function connect(){
		return new PDO("mysql:host=".$this->getHost().";
			dbname=".$this->getDB()."", 
			"".$this->getUsr()."", 
			"".$this->getPass().""
			);
	}

}