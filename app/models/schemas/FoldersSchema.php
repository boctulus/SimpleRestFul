<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class FoldersSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'folders',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'tb' => 'STR',
			'name' => 'STR',
			'belongs_to' => 'INT'
		],

			'nullable'		=> ['id'],

			'rules' 		=> [
				'tb' => ['max' => 40],
				'name' => ['max' => 40]
			]
		];
	}	
}

