<?php
declare(strict_types=1);

include CORE_PATH. 'model.php';

class UsersModel extends Model
 {
	static protected $table_name = "users";
	static protected $id_name = 'id';
	static protected $fillable = [
							'username',
							'password',
							'firstname',
							'lastname',
							'email'
	];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	static protected $schema = [
		'id' => 'INT',
		'username' => 'STR',
		'password' => 'STR',
		'firstname' => 'STR',
		'lastname'=> 'STR',
		'email' => 'STR'
	];

    public function __construct($db = NULL){
        parent::__construct($db);
    }
	
	function getUserIfExists()
	{
		$q  = "SELECT * FROM ".static::$table_name." WHERE username=? AND password=?";
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