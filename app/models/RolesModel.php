<?php

namespace simplerest\models;

use simplerest\core\Model;

class RolesModel extends Model
 {
	protected $table_name = "roles";
	protected $id_name = 'id';
	protected $fillable = ['name'];
	protected $hidden = [ ];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'name' => 'STR',
		'is_admin' => 'INT'
	];


    public function __construct($db = NULL){
        parent::__construct($db);
    }
	
	
}