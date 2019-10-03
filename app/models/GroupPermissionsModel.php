<?php
namespace simplerest\models;

use simplerest\core\Model;

class GroupPermissionsModel extends Model 
{
	protected $table_name = "group_permissions";
	protected $id_name = 'id';
	protected $fillable = ['resource_table', 'register', 'owner', 'member', 'g_read', 'g_write'];
  
	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
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







