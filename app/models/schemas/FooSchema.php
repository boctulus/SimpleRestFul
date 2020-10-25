<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class FooSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'foo',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'bar' => 'STR',
			'hide' => 'INT',
			'deleted_at' => 'STR'
		],

			'nullable'		=> ['deleted_at'],

			'rules' 		=> [
				'bar' => ['max' => 45]
			]
		];
	}	
}

