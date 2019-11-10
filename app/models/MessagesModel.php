<?php

namespace simplerest\models;

use simplerest\core\Model;
use simplerest\libs\Factory;

class MessagesModel extends Model
 { 
	protected $nullable = ['to_name', 'attempts', 'sent_at'];

	protected $schema = [
		'id' => 'INT',
		'from_email' => 'STR',
		'from_name' => 'STR',
		'to_email' => 'STR',
		'to_name' => 'STR',
		'subject' => 'STR',
		'body'=> 'STR',
		'created_at' => 'STR',
		'attempts' => 'INT',
		'sent_at' => 'INT'
	];

	protected $rules = [
		'from_email' 	=> ['type' => 'email'],
		'from_name' 	=> ['min'=>3, 'max'=>60],
		'to_email' 	=> ['type' => 'email'],
		'to_name' 	=> ['min'=>3, 'max'=>60],
		'body' => ['min'=> 3]	
	];

    public function __construct($db = NULL){
        parent::__construct($db);
    }	
	
}