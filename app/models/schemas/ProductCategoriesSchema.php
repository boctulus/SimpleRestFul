<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class ProductCategoriesSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'product_categories',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'name' => 'STR'
		],

			'nullable'		=> ['id'],

			'rules' 		=> [
				'name' => ['max' => 80]
			]
		];
	}	
}

