<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class __NAME__ implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> __TABLE_NAME__,

			'id_name'		=> __ID__,

			'attr_types'	=> __ATTR_TYPES__,

			'nullable'		=> __NULLABLES__,

			'rules' 		=> __RULES__
		];
	}	
}

