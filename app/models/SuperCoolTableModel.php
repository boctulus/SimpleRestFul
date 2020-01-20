<?php
namespace simplerest\models;

use simplerest\core\Model;


class SuperCoolTableModel extends Model 
{
	protected $table_name = "super_cool_table";
	protected $id_name = 'id';
	protected $nullable = ['active'];

	protected $schema = [
		'id' 		 => 'INT',
		'name'		 => 'STR',
		'active'	 => 'INT',
		'belongs_to' => 'INT',
		'created_at' => 'STR',
		'created_by' => 'INT',
		'updated_at' => 'STR',
		'updated_by' => 'INT', 
		'deleted_at' => 'STR',
		'deleted_by' => 'INT',
		'locked'	 => 'INT' 
	];

	protected $rules = [
		'active' => ['type' => 'bool']
	];

    function __construct($db = NULL){
		parent::__construct($db);
	}

}







