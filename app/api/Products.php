<?php

namespace simplerest\api;

use simplerest\core\ApiController;
use simplerest\libs\Factory;
use simplerest\libs\Database;
use simplerest\models\UsersModel;

class Products extends ApiController
{ 
    function __construct()
    {
        // CORS
        $headers = [
            'access-control-allow-Origin' => '*'
        ];
   
        // Si el usuario no está habilitado, expulsarlo
        $auth = new \simplerest\controllers\AuthController();
        $auth->addmust_have([ 'enabled' => 1 ], 403, 'Usuario no habilitado');
        $auth->addmust_not([ 'quota' => 0 ], 403, 'Quota exceded');      
        
        parent::__construct($headers, $auth);
    }

        
} // end class
