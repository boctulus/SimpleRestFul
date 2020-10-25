<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class UsersSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'users',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'username' => 'STR',
			'active' => 'INT',
			'locked' => 'INT',
			'email' => 'STR',
			'confirmed_email' => 'INT',
			'firstname' => 'STR',
			'lastname' => 'STR',
			'password' => 'STR',
			'deleted_at' => 'STR',
			'belongs_to' => 'INT'
		],

			'nullable'		=> ['id', 'active', 'confirmed_email', 'firstname', 'lastname', 'password', 'deleted_at', 'belongs_to'],

			'rules' 		=> [
				'username' => ['max' => 15],
				'email' => ['max' => 60],
				'firstname' => ['max' => 50],
				'lastname' => ['max' => 80],
				'password' => ['max' => 60]
			]
		];
	}	
}

