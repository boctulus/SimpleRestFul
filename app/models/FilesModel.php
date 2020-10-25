<?php

namespace simplerest\models;

use simplerest\core\Model;
use simplerest\libs\ValidationRules;
use simplerest\models\schemas\FilesSchema;

class FilesModel extends Model
 { 
	### TRAITS
	### PROPERTIES

	protected $hidden   = [];
	protected $not_fillable = ['filename_as_stored']; 

    function __construct(bool $connect = false){
        parent::__construct($connect, new FilesSchema());
	}	
}

