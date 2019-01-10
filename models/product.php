<?php
declare(strict_types=1);

include CORE_PATH. 'model.php';

/*
	Product extends Model to have access to reflection
	Another way could be to use traits 
*/
class ProductModel extends Model 
{
	static protected $table_name = "products";
	static protected $id_name = 'id';
	static protected $fillable = ['name','description','size','cost'];
 
	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	static protected $schema = [
		'id' => 'INT',
		'name' => 'STR',
		'description' => 'STR',
		'size' => 'STR',
		'cost' => 'INT'
	];

    public function __construct($db = NULL){
		parent::__construct($db);
	}

}







