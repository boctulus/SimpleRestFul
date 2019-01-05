<?php

include CORE_PATH. 'model.php';

class User extends Model
 {
    private $table_name = "users";
 
    // entity properties
    public $id;
    public $username;
    public $password;
 
	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'username' => 'STR',
		'password' => 'STR'
	];

    public function __construct($db){
        parent::__construct($db);
    }

	/*
		CRUD operations
	*/
	
	function readById()
	{
		$q  = "SELECT * FROM {$this->table_name} WHERE id=:id";
		$st = $this->conn->prepare( $q );
		$st->bindParam(":id", $this->id, PDO::PARAM_INT);
		$st->execute();
	
		$row = $st->fetch(PDO::FETCH_OBJ);
	 
		if ($row){
			$this->username = $row->username;
			$this->password = $row->password;
			$this->token = $row->token;
			$this->tokenExpiration = $row->tokenExpiration;
			return true;
		}
		
		return false;
	}
	
	function exists()
	{
		$q  = "SELECT * FROM {$this->table_name} WHERE username=? AND password=?";
		$st = $this->conn->prepare($q);
		$pass = sha1($this->password);
		$st->execute([$this->username,sha1($this->password)]);
	
		$row = $st->fetch(PDO::FETCH_OBJ);
		
		if ($row){
			$this->id = $row->id;
			$this->username = $row->username;
			$this->password = $row->password;
			return true;
		}
		
		return false;
	}
	
	
	function read()
	{
		$q  = "SELECT * FROM {$this->table_name} ORDER BY id";
		$st = $this->conn->prepare($q);
		$result = $st->execute();
	 
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function create()
	{
		
	}
	
	
	function update()
	{
		$q = "UPDATE {$this->table_name} SET
					username = :username,
					password = :password,
					token = :token,
					tokenExpiration = :tokenExpiration
				WHERE
					id = :id";
	 
		$st = $this->conn->prepare($q);
	 
		$st->bindParam(':id', $this->id);
		$st->bindParam(':username', $this->username);
		$st->bindParam(':password', $this->password);
		
		return ($st->execute());
	}
	
	
	function delete()
	{
	
	}
	
	
}