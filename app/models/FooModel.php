<?php
namespace simplerest\models;

use simplerest\core\Model;

class FooModel extends Model 
{
	protected $table_name = "foo";
	protected $id_name = 'id';

	protected $schema = [
		'id' => 'INT',
		'bar' => 'STR',
		'deleted_at' => 'STR'
	];

	protected $rules = [
		'bar' => ['type' => 'alpha']
	];

    function __construct($db = NULL){
		parent::__construct($db);
	}

}







