<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class CollectionsSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'collections',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'entity' => 'STR',
			'refs' => 'STR',
			'belongs_to' => 'INT',
			'created_at' => 'STR'
		],

			'nullable'		=> ['id'],

			'rules' 		=> [
				'entity' => ['max' => 80]
			]
		];
	}	
}

