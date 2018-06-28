<?php
class Database{

	public $conn;
  
	public function __construct($db_name,$host='localhost',$user='root',$pass=''){
		try {
			$this->conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $user, $pass);
            $this->conn->exec("set names utf8");
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		} catch (PDOException $e) {
			die($e->getMessage());
		}	
	}

}

