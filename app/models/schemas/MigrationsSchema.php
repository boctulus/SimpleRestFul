<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class MigrationsSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'migrations',

			'id_name'		=> 'id',

			'attr_types'	=> [
				'id' => 'STR',
				'migration' => 'STR',
				'batch' => 'INT'
			],

			'nullable'		=> ['id'],

			'rules' 		=> [
				'migration' => ['max' => 255]
			]
		];
	}	
}

