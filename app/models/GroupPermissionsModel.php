<?php
namespace simplerest\models;

use simplerest\core\Model;

class GroupPermissionsModel extends Model 
{
	static protected $table_name = "group_permissions";
	static protected $id_name = 'id';
	static protected $fillable = ['resource_table', 'register', 'owner', 'member', 'g_read', 'g_write'];
 
	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	static protected $schema = [
		'id' => 'INT',
		'resource_table' => 'STR',
		'register' => 'INT',
		'owner' => 'INT',
		'member' => 'INT',
		'g_read' => 'INT',
		'g_write' => 'INT'		
	];

    public function __construct($db = NULL){
		parent::__construct($db);
	}

}







