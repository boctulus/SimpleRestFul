<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class Baz2Schema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'baz2',

			'id_name'		=> 'id_baz2',

			'attr_types'	=> [
			'id_baz2' => 'INT',
			'name' => 'STR',
			'cost' => 'STR'
		],

			'nullable'		=> ['id_baz2'],

			'rules' 		=> [
				'name' => ['max' => 45]
			]
		];
	}	
}

