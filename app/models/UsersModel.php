<?php

namespace simplerest\models;

use simplerest\core\Model;
use simplerest\libs\Factory;

class UsersModel extends Model
 { 
	protected $table_name = "users";
	protected $id_name = 'id';
	/*
	protected $fillable = [
							'email',
							'password',
							'confirmed_email',
							'firstname',
							'lastname',
							'deleted_at',
							'belongs_to'
	];
	*/
	protected $nullable = ['id', 'firstname', 'lastname', 'deleted_at', 'belongs_to', 'confirmed_email'];
	protected $hidden   = [	'password' ];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'email' => 'STR',
		'confirmed_email' => 'INT',
		'password' => 'STR',
		'firstname' => 'STR',
		'lastname'=> 'STR',
		'deleted_at' => 'STR',
		'belongs_to' => 'INT'
	];

    public function __construct($db = NULL){
        parent::__construct($db);
    }
	
}