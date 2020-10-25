<?php

namespace simplerest\models\schemas;

use simplerest\core\interfaces\ISchema;

### IMPORTS

class BarSchema implements ISchema
{ 
	### TRAITS
	
	function get(){
		return [
			'table_name'	=> 'bar',

			'id_name'		=> 'uuid',

			'attr_types'	=> [
				'uuid' => 'STR',
				'name' => 'STR',
				'price' => 'STR',
				'email' => 'STR',
				'belongs_to' => 'INT',
				'created_at' => 'STR',
				'updated_at' => 'STR'
			],

			'nullable'		=> ['created_at', 'updated_at', 'uuid'],

			'rules' 		=> [
				'uuid' => ['max' => 36],
				'name' => ['max' => 50],
				'email' => ['max' => 80]
			]
		];
	}	

	// cambios críticos
	function getTransition(){
		return [
			// cambia de nombre
			'table_name' => 'bar_r',

			// renombrado de atributos
			// old -> new
			'attributes' => [
				'price' => 'cost',
				'email' => 'correo'
			],

			// cambio de constante para PDO
			'attr_types'	=> [
				'price' => 'INT'
			],

			// se vuelven no-nullables
			'not_nullable'		=> [
				'updated_at'
			],

			// nuevas reglas
			'rules'	=> [
				'name' => ['max' => 45], // más restrictiva
 				'email' => ['max' => 100]
			]

		];	

		// <-- también el id_name podría cambiar 
	}
}

