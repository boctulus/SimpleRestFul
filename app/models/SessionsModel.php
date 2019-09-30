<?php
declare(strict_types=1);

namespace simplerest\models;

use simplerest\core\Model;

/*
	Product extends Model to have access to reflection
	Another way could be to use traits 
*/
class SessionsModel extends Model 
{
	static protected $table_name = "sessions";
	static protected $id_name = 'id';
	static protected $fillable = [ 'refresh_token', 'login_date', 'user_id', 'role' ];
 
	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	static protected $schema = [
		'id' => 'INT',
		'refresh_token' => 'STR',
		'login_date' => 'INT',
		'user_id' => 'INT',
		'role' => 'INT'
	];

    public function __construct($db = NULL){
		parent::__construct($db);
	}
}







