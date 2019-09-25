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

        parent::__construct($headers, $auth);
        
        /*
        // Otra forma de conseguir lo mismo:

        parent::__construct($headers, $auth);

        $conn    = Database::getConnection($this->config['database']);
        
        $u = new UsersModel($conn);
        $u->id = $this->auth_payload->id;
        $u->fetch(['enabled']); 

        if ($u->enabled == 0)
            Factory::response()->sendError('Usuario no habilitado', 403);
        */
    }



        
} // end class
