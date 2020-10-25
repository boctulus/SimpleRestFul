<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class SuperCoolTableSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'super_cool_table',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'name' => 'STR',
			'age' => 'INT',
			'active' => 'INT',
			'belongs_to' => 'INT',
			'deleted_at' => 'STR',
			'locked' => 'INT'
		],

			'nullable'		=> ['id', 'deleted_at'],

			'rules' 		=> [
				'name' => ['max' => 45]
			]
		];
	}	
}

