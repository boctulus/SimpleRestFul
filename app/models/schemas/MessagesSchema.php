<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class MessagesSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'messages',

			'id_name'		=> 'id',

			'attr_types'	=> [
			'id' => 'INT',
			'from_email' => 'STR',
			'from_name' => 'STR',
			'to_email' => 'STR',
			'to_name' => 'STR',
			'subject' => 'STR',
			'body' => 'STR',
			'created_at' => 'STR',
			'sent_at' => 'STR'
		],

			'nullable'		=> ['id', 'to_name', 'sent_at'],

			'rules' 		=> [
				'from_email' => ['max' => 320],
				'from_name' => ['max' => 60],
				'to_email' => ['max' => 320],
				'to_name' => ['max' => 40],
				'subject' => ['max' => 40]
			]
		];
	}	
}

