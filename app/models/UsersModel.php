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
							'firstname',
							'lastname',
							'deleted_at',
							'belongs_to'
	];
	*/

	protected $not_fillable = ['confirmed_email'];
	protected $nullable = ['id', 'firstname', 'lastname', 'deleted_at', 'belongs_to', 'confirmed_email'];
	protected $hidden   = [	'password' ];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'username' => 'STR',
		'email' => 'STR',
		'confirmed_email' => 'INT',
		'password' => 'STR',
		'firstname' => 'STR',
		'lastname'=> 'STR',
		'deleted_at' => 'STR',
		'belongs_to' => 'INT'
	];

	protected $rules = [
		'username' => ['min'=>2, 'max'=>15],
		'email' 	=> ['type'=>'email'],
	];

    public function __construct($db = NULL){
        parent::__construct($db);
    }
	
}