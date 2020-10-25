<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class UserTbPermissionsSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'user_tb_permissions',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'tb' => 'STR',
			'can_list_all' => 'INT',
			'can_show_all' => 'INT',
			'can_list' => 'INT',
			'can_show' => 'INT',
			'can_create' => 'INT',
			'can_update' => 'INT',
			'can_delete' => 'INT',
			'user_id' => 'INT',
			'created_by' => 'INT',
			'created_at' => 'STR',
			'updated_at' => 'STR'
		],

			'nullable'		=> ['id', 'can_list_all', 'can_show_all', 'can_list', 'can_show', 'can_create', 'can_update', 'can_delete', 'created_by', 'updated_at'],

			'rules' 		=> [
				'tb' => ['max' => 80]
			]
		];
	}	
}

