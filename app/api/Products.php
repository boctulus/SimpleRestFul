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

        parent::__construct($headers);
        
        // Si el usuario no está habilitado, expulsarlo
        $conn    = Database::getConnection($this->config['database']);
        
        $u = new UsersModel($conn);
        $u->id = $this->auth_payload->id;
        $u->fetch(['enabled']); 

        if ($u->enabled == 0)
            Factory::response()->sendError('Usuario deshabilidado', 401);
    }

        
} // end class
