<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class ProductsSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'products',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'name' => 'STR',
			'description' => 'STR',
			'size' => 'STR',
			'cost' => 'INT',
			'created_at' => 'STR',
			'created_by' => 'INT',
			'updated_at' => 'STR',
			'updated_by' => 'INT',
			'deleted_at' => 'STR',
			'deleted_by' => 'INT',
			'active' => 'INT',
			'locked' => 'INT',
			'workspace' => 'STR',
			'belongs_to' => 'INT'
		],

			'nullable'		=> ['id', 'description', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'active', 'locked', 'workspace', 'belongs_to'],

			'rules' 		=> [
				'name' => ['max' => 50],
				'description' => ['max' => 240],
				'size' => ['max' => 30],
				'workspace' => ['max' => 40]
			]
		];
	}	
}

