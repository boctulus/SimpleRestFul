<?php

namespace simplerest\models;

use simplerest\core\Model;

class FoldersModel extends Model 
{
	protected $table_name = "folders";
	protected $id_name = 'id';

	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'resource_table' => 'STR',
		'name' => 'STR',
		'belongs_to' => 'INT'	
	];

    public function __construct($db = NULL){
		parent::__construct($db);
	}

}







