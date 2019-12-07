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
	protected $nullable = ['description', 'size', 'active', 'locked', 'workspace', 'created_at', 'modified_at', 'deleted_at' ];
 
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
		'active' => 'INT',
		'locked' => 'INT',		 
		'belongs_to' => 'INT' 
	];

	protected $rules = [
        'name' 			=> ['min'=>3, 'max'=>40],
		'description' 	=> ['max'=>50],
		'size' 			=> ['max'=>20],
        'workspace'		=> ['max'=>20],
		'created_at' 	=> ['type'=>'datetime'],
		'modified_at' 	=> ['type'=>'datetime'],
		'deleted_at' 	=> ['type'=>'datetime'],
	];

    public function __construct($db = NULL){
		parent::__construct($db);
	}

}







