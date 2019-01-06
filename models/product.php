<?php
declare(strict_types=1);

include CORE_PATH. 'model.php';

/*
	Product extends Model to have access to reflection
	Another way could be to use traits 
*/
class Product extends Model 
{
	protected $table_name = "products";
	protected $id_name = 'id';
 
	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'name' => 'STR',
		'description' => 'STR',
		'size' => 'STR',
		'cost' => 'INT'
	];

    public function __construct($db){
		parent::__construct($db);
	}

}







