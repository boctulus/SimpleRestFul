<?php
declare(strict_types=1);

namespace Models;

require CORE_PATH . 'model.php';

class UsersModel extends \Core\Model
 {
	static protected $table_name = "users";
	static protected $id_name = 'id';
	static protected $fillable = [
							'email',
							'password',
							'firstname',
							'lastname'
	];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	static protected $schema = [
		'id' => 'INT',
		'email' => 'STR',
		'password' => 'STR',
		'firstname' => 'STR',
		'lastname'=> 'STR'
	];

	/*
		Unique constraints
	*/
	//static protected $unique = [
	//	['email']
	//];

    public function __construct($db = NULL){
        parent::__construct($db);
    }
	
	function checkUserAndPass()
	{
		$q  = "SELECT * FROM ".static::$table_name." WHERE email=? AND password=?";
		$st = $this->conn->prepare($q);
		$st->execute([$this->email, sha1($this->password)]);
	
		$row = $st->fetch(\PDO::FETCH_OBJ);
		
		if ($row){
			$this->id = $row->id;
			$this->email = $row->email;
			$this->password = $row->password;
			return true;
		}
		
		return false;
	}
	
	
}