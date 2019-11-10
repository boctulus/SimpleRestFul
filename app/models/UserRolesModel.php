<?php
declare(strict_types=1);

namespace simplerest\models;

use simplerest\core\Model;

class UserRolesModel extends Model
 {
	protected $table_name = "user_roles";
	protected $id_name = 'id';
	//protected $fillable = [ 'user_id', 'role_id', 'creation_date', 'modification_date'];
	protected $hidden = [ ];
	protected $nullable = ['creation_date', 'modification_date'];

	/*
		Unique constraints
	*/
	//static protected $unique = [
	//	['user_id', 'role_id']
	//];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'user_id' => 'INT',
		'role_id' => 'INT',
		'creation_date'  => 'STR',
		'modification_date'  => 'STR'
	];

    function __construct($db = NULL){
        parent::__construct($db);
    }
	
	
}