<?php

namespace simplerest\controllers\api;

use simplerest\controllers\MyApiController; 
use simplerest\libs\Factory;
use simplerest\libs\DB;
use simplerest\libs\Validator;
use simplerest\core\exceptions\InvalidValidationException;
use simplerest\libs\Debug;

class UserSpPermissions extends MyApiController
{    
    function __construct()
    {

        // Falta limitar acceso !!!
        $this->callable = ['post', 'put', 'get', 'put', 'patch'];

        $this->is_listable = true;
        $this->is_retrievable = true;
                
        parent::__construct();
    }

        
} // end class
