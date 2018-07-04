<?php
class User{
 
    private $conn;
    private $table_name = "users";
 
    // entity properties
    public $id;
    public $username;
    public $password;
	public $token;
    public $tokenExpiration;
 
    public function __construct($db){
        $this->conn = $db;
    }

	/*
		CRUD operations
	*/
	
	function read()
	{
		$q  = "SELECT * FROM {$this->table_name} WHERE id=:id";
		$st = $this->conn->prepare( $q );
		$st->bindParam(":id", $this->id, PDO::PARAM_INT);
		$st->execute();
	
		$row = $st->fetch(PDO::FETCH_OBJ);
	 
		$this->username = $row->username;
		$this->token = $row->token;
		$this->tokenExpiration = $row->tokenExpiration;
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
			$this->token = $row->token;
			$this->tokenExpiration = $row->tokenExpiration;
			
			return true;
		}
		
		return false;
	}
	
	
	
	function readAll()
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
		$st->bindParam(':token', $this->token);
		$st->bindParam(':tokenExpiration', $this->tokenExpiration);
	 
		return ($st->execute());
	}
	
	function delete()
	{
	
	}
	
	
}