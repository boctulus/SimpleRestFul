<?php

namespace simplerest\models;

use simplerest\core\Model;
use simplerest\libs\Factory;

class UsersModel extends Model
 { 
	protected $table_name = "users";
	protected $id_name = 'id';
	protected $not_fillable = ['confirmed_email', 'active'];
	protected $nullable = ['firstname', 'lastname', 'active', 'confirmed_email'];
	protected $hidden   = [	'password' ];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'username' => 'STR',
		'active' => 'INT',
		'email' => 'STR',
		'confirmed_email' => 'INT',
		'password' => 'STR',
		'firstname' => 'STR',
		'lastname'=> 'STR',
		'deleted_at' => 'STR',
		'belongs_to' => 'INT'
	];

	protected $rules = [
		'active' 	=> ['type' => 'bool'],
		'username'	=> ['min' => 2, 'max'=> 15, 'type' => 'regex:/^[a-zA-Z0-9_]+$/', 'messages' => ['type' => 'Invalid characters'] ], 
		'email' 	=> ['type'=>'email']
	];

    function __construct($db = NULL){		
		$this->accesorRegister('password', function($pass){ return password_hash($pass, PASSWORD_DEFAULT); });
        parent::__construct($db);
    }
	
}