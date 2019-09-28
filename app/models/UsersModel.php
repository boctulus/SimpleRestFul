<?php
declare(strict_types=1);

namespace simplerest\models;

use simplerest\core\Model;

class UsersModel extends Model
 {
	static protected $table_name = "users";
	static protected $id_name = 'id';
	static protected $fillable = [
							'email',
							'password',
							'firstname',
							'lastname'
	];

	static protected $hidden = ['password'];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	static protected $schema = [
		'id' => 'INT',
		'email' => 'STR',
		'password' => 'STR',
		'firstname' => 'STR',
		'lastname'=> 'STR',
		'enabled' => 'INT',
		'quota' => 'INT'
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
	
	/*
		Usar password_hash / password_verify en su lugar
	*/
	function checkUserAndPass()
	{
		$q  = "SELECT * FROM ".static::$table_name." WHERE email=? AND password=?";
		$st = $this->conn->prepare($q);
		$st->execute([$this->email, sha1($this->password)]);
	
		$row = $st->fetch(\PDO::FETCH_OBJ);
		
		if ($row){
			foreach ($row as $k => $field){
				$this->{$k} = $row->$k;
			}
			return true;
		}
		
		return false;
	}
	
	
}