<?php
namespace simplerest\models;

use simplerest\core\Model;

class OtherPermissionsModel extends Model 
{
	static protected $table_name = "other_permissions";
	static protected $id_name = 'id';
	static protected $fillable = ['resource_table', 'owner', 'o_read', 'o_write'];
 
	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	static protected $schema = [
		'id' => 'INT',
		'resource_table' => 'STR',
		'owner' => 'INT',
		'o_read' => 'INT',
		'o_write' => 'INT'		
	];

    public function __construct($db = NULL){
		parent::__construct($db);
	}

}







