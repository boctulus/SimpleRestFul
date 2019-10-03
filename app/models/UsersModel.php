<?php
declare(strict_types=1);

namespace simplerest\models;

use simplerest\core\Model;

class UsersModel extends Model
 {
	protected $table_name = "users";
	protected $id_name = 'id';
	protected $fillable = [
							'email',
							'password',
							'firstname',
							'lastname',
							'belongs_to'
	];

	protected $hidden = ['password'];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'email' => 'STR',
		'password' => 'STR',
		'firstname' => 'STR',
		'lastname'=> 'STR',
		'enabled' => 'INT',
		'quota' => 'INT',
		'belongs_to' => 'INT'
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
		$q  = "SELECT * FROM ".$this->table_name." WHERE email=? AND password=?";
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
	
	/*
		@return mixed false | array of all available roles for the user
	*/
	function fetchRoles(){
		$q  = "SELECT ur.role_id as role FROM " . $this->table_name. ' as u INNER JOIN user_role as ur ON ur.user_id=u.id INNER JOIN roles AS r ON ur.role_id=r.id' . ' WHERE u.'.$this->id_name . '=?';
		$st = $this->conn->prepare($q);
		$st->execute([$this->id]);
	
		$rows = $st->fetchAll(\PDO::FETCH_ASSOC);

		if (!empty($rows)){
			$roles = [];
			foreach ($rows as $row){
				$roles[] = $row['role'];	
			}
			return $roles;
		}
	
		return false;
	}
	
}