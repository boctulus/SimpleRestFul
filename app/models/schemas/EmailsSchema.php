<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class EmailsSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'emails',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'text' => 'STR',
			'confirmed' => 'INT',
			'user_id' => 'INT'
		],

			'nullable'		=> [],

			'rules' 		=> [
				'text' => ['max' => 60]
			]
		];
	}	
}

