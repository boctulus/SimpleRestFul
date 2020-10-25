<?php

namespace simplerest\models;

use simplerest\core\Model;
use simplerest\libs\ValidationRules;
use simplerest\models\schemas\BarSchema;
use simplerest\traits\Uuids;

class BarModel extends Model
{ 
	use Uuids;
	

	protected $hidden   = [];
	protected $not_fillable = [];

    function __construct(bool $connect = false){
        parent::__construct($connect, new BarSchema());
	}	
}

