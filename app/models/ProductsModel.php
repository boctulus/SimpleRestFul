<?php

namespace simplerest\models;

use simplerest\core\Model;
use simplerest\libs\ValidationRules;
use simplerest\models\schemas\ProductsSchema;

class ProductsModel extends Model
 { 
	### TRAITS
	### PROPERTIES

	protected $hidden   = [];
	protected $not_fillable = [];

    function __construct(bool $connect = false){
        parent::__construct($connect, new ProductsSchema());
	}	
}

