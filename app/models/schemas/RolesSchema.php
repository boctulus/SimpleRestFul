<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class RolesSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'roles',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'name' => 'STR'
		],

			'nullable'		=> ['id'],

			'rules' 		=> [
				'name' => ['max' => 50]
			]
		];
	}	
}

