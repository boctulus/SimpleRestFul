<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class UserSpPermissionsSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'user_sp_permissions',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'sp_permission_id' => 'INT',
			'user_id' => 'INT',
			'created_by' => 'INT',
			'created_at' => 'STR',
			'updated_at' => 'STR'
		],

			'nullable'		=> ['id', 'created_by', 'updated_at'],

			'rules' 		=> [

			]
		];
	}	
}

