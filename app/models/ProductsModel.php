<?php
namespace simplerest\models;

use simplerest\core\Model;

/*
	Product extends Model to have access to reflection
	Another way could be to use traits 
*/
class ProductsModel extends Model 
{
	protected $table_name = "products";
	protected $id_name = 'id';
	//protected $fillable = ['name','description','size','cost', 'deleted_at', 'workspace', 'belongs_to'];
	protected $nullable = ['description', 'workspace', 'created_at', 'modified_at', 'deleted_at' ];
 
	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'id' => 'INT',
		'name' => 'STR',
		'description' => 'STR',
		'size' => 'STR',
		'cost' => 'INT',
		'workspace' => 'STR',
		'created_at' => 'STR',
		'modified_at' => 'STR',
		'deleted_at' => 'STR',
		'locked' => 'INT',		 
		'belongs_to' => 'INT' 
	];

    public function __construct($db = NULL){
		parent::__construct($db);
	}

}







