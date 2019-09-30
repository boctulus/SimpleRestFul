<?php

namespace simplerest\models;

use simplerest\core\Model;

class RolesModel extends Model
 {
	static protected $table_name = "roles";
	static protected $id_name = 'id';
	static protected $fillable = [ 'name'];
	static protected $hidden = [ ];

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	static protected $schema = [
		'id' => 'INT',
		'name' => 'STR'
	];


    public function __construct($db = NULL){
        parent::__construct($db);
    }
	
	
}