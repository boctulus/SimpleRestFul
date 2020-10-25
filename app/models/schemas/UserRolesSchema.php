<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class UserRolesSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'user_roles',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'user_id' => 'INT',
			'role_id' => 'INT',
			'created_at' => 'STR',
			'updated_at' => 'STR'
		],

			'nullable'		=> ['id', 'updated_at'],

			'rules' 		=> [

			]
		];
	}	
}

