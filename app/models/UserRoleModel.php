<?php
declare(strict_types=1);

namespace simplerest\models;

use simplerest\core\Model;

class UserRoleModel extends Model
 {
	static protected $table_name = "user_role";
	static protected $id_name = 'id';
	static protected $fillable = [ 'user_id', 'role_id', 'creation_date', 'modification_date'];
	static protected $hidden = [ ];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	static protected $schema = [
		'id' => 'INT',
		'user_id' => 'INT',
		'role_id' => 'INT',
		'creation_date'  => 'INT',
		'modification_date'  => 'INT'
	];

	/*
		Unique constraints
	*/
	//static protected $unique = [
	//	['user_id', 'role_id']
	//];


    public function __construct($db = NULL){
        parent::__construct($db);
    }
	
	
}