<?php

namespace simplerest\models;

use simplerest\core\Model;
use simplerest\libs\ValidationRules;
use simplerest\models\schemas\FooSchema;

class FooModel extends Model
 { 
	### TRAITS
	### PROPERTIES

	protected $hidden   = [];
	protected $not_fillable = [];

    function __construct(bool $connect = false){
        parent::__construct($connect, new FooSchema());
	}	
}

