<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class FolderPermissionsSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'folder_permissions',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'folder_id' => 'INT',
			'belongs_to' => 'INT',
			'access_to' => 'INT',
			'r' => 'INT',
			'w' => 'INT'
		],

			'nullable'		=> ['id'],

			'rules' 		=> [

			]
		];
	}	
}

