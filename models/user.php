<?php
declare(strict_types=1);

include CORE_PATH. 'model.php';

class User extends Model
 {
	protected $table_name = "users";
	protected $id_name = 'id';
 
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
	
	function getUserIfExists()
	{
		$q  = "SELECT * FROM {$this->table_name} WHERE username=? AND password=?";
		$st = $this->conn->prepare($q);
		$st->execute([$this->username, sha1($this->password)]);
	
		$row = $st->fetch(PDO::FETCH_OBJ);
		
		if ($row){
			$this->id = $row->id;
			$this->username = $row->username;
			$this->password = $row->password;
			return true;
		}
		
		return false;
	}
	
	
}